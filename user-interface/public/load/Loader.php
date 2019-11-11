<?php


namespace App\load;


use phpDocumentor\Reflection\Types\Object_;

class Loader
{

    private $url;
    private $port;
    private $headers;

    public function __construct(string $url, int $port)
    {
        $this->url = $url;
        $this->port = $port;
        $this->headers = array(
            "Authorization"=>"",
            "API-Version"=>"",
            "X-NLX-Logrecord-ID"=>"",
            "X-NLX-Request-Process-ID"=>"",
            "X-NLX-Request-Data-Elements"=>"",
            "X-NLX-Request-Data-Subject"=>"",
            "X-Audit-Clarification",
        );
    }
    public function get(string $uri)
    {

        $result = http_get("$this->url/$uri", array("timeout"=>1, "port"=>$this->port, "headers"=>$this->headers), $info);
        //$result = "hi!";
        //$info= array("response_code"=>200);
        if($info['response_code'] == 200){
            return $result;
        }
        else{
            return "FAIL!";
        }

    }
    public function postMember(string $firstname,
                               string $lastname,
                               string $email,
                               string $dateOfBirth,
                               string $userName,
                               string $passWord,
                               array $roles,
                               array $organisations
    )
    {
        $memberObject = (object)[];
        $rolesArray = array();

        foreach($roles as $role){
            $roleObj = (object)[];
            $roleObj->id = $role;
            array_push($rolesArray, $roleObj);
        }
        $organisationsArray = array();
        foreach($organisations as $organisation){
            $organisationObj = (object)[];
            $organisationObj->id = $organisation;
            array_push($organisationsArray, $organisationObj);
        }


        $memberObject->firstName = $firstname;
        $memberObject->lastName = $lastname;

        $memberObject->email = $email;

        $memberObject->dateOfBirth = $dateOfBirth;

        $memberObject->userName = $userName;
        $memberObject->passWord = $passWord;

        $memberObject->roles = $rolesArray;
        $memberObject->organisations = $organisationsArray;

        $request = json_encode($memberObject);

        //var_dump(get_object_vars($memberObject));
        var_dump($request);

        //http_post_data($url.'/members',$request, ["port"=>$this->port], $info);

        //var_dump($info);
        return $memberObject;
    }

}
