<?php


namespace App\load;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

//use GuzzleHttp\Exception\ClientErrorResponseException;

class Loader
{

    private $url;
    private $port;
    private $generalHeaders;
    private $client;

    public function __construct(string $url, int $port)
    {
        $this->url = $url;
        $this->port = $port;
        $this->client = new Client([
            'base_uri' => "$url:$port",
            'timeout' => 10.0
        ]);
        $this->generalHeaders = array(
            "Authorization"=>"",
            "API-Version"=>"",
            "X-NLX-Logrecord-ID"=>"",
            "X-NLX-Request-Process-ID"=>"",
            "X-NLX-Request-Data-Elements"=>"",
            "X-NLX-Request-Data-Subject"=>"",
            "X-Audit-Clarification",
        );
    }

    private function parseRoles($roles){
        $rolesArray = array();
        foreach($roles as $role)
        {
            $roleObj = (object)[];
            $roleObj->id = $role;
            array_push($rolesArray, $roleObj);
        }
        return $rolesArray;
    }
    private function parseMembers($members){
        $membersArray = array();
        foreach($members as $member)
        {
            $memberObj = (object)[];
            $memberObj->id = $member;
            array_push($membersArray, $memberObj);
        }
        return $membersArray;
    }
    private function parseOrganisations($organisations){
        $organisationsArray = array();
        foreach($organisations as $organisation){
            $organisationObj = (object)[];
            $organisationObj->id = $organisation;
            array_push($organisationsArray, $organisationObj);

        }
        return $organisationsArray;
    }
    private function parseTag(string $name, string $organisation):stdClass
    {
        $existingTags = $this->get('/Tags');
        $tag = (object)[];
        foreach($existingTags as $existingTag)
        {
            if ($name == $existingTag->name &&
                $organisation == $existingTag->organisation)
            {
                return $existingTag;
            }
        }
        $tag->name = $name;
        $tag->organisation = $organisation;

        return $tag;

    }
    private function parseTags(array $tags){
        $tagsArray = array();
        foreach($tags as $tag){
            $tag = $this->parseTag($tag->name, $tag->organisation);
            array_push($tagsArray, $tag);
        }
        return $tagsArray;
    }
    private function parseMemberToCreate(string $firstname,
                                         string $lastname,
                                         string $email,
                                         string $dateOfBirth,
                                         string $userName,
                                         string $passWord,
                                         array $roles,
                                         array $organisations,
                                         bool $contributionPaid,
                                         string $membershipEndDate,
                                         ?array $tags
    )
        :stdClass
    {
        $member = (object)[];

        $member->firstName = $firstname;
        $member->lastName = $lastname;

        $member->email = $email;

        $member->dateOfBirth = $dateOfBirth;

        $member->userName = $userName;
        $member->passWord = $passWord;

        $member->roles = $this->parseRoles($roles);
        $member->organisations = $this->parseOrganisations($organisations);
        $member->contributionPaid = $contributionPaid;
        $member->membershipEndDate = $membershipEndDate;
        if($tags)
            $member->tags = $this->parseTags($tags);

        return $member;
    }

    private function parseAddress(string $street,
                                          string $number,
                                          string $postalCode,
                                          string $settlement,
                                          ?string $province,
                                          string $country
                                          ){
        $addresses = $this->get('/Addresses');
        $address = (object)[];
        foreach($addresses as $addressFound)
        {
            if($addressFound->number == $number && $addressFound->postalCode == $postalCode && $addressFound->country == $country){
                $address->id = $addressFound->id;
                return $address;
            }
        }
        $address->street = $street;
        $address->number = $number;
        $address->postalCode = $postalCode;
        $address->settlement = $settlement;
        if ($province)
            $address->province = $province;
        $address->country = $country;

        return $address;
    }
    private function parseOrganisationToCreate(string $name,
                                               int $organisationNumber,
                                               string $goal,
                                               stdClass $location,
                                               array $members,
                                               array $roles
    )
    {
        $organisation = (object)[];

        $organisation->name = $name;
        $organisation->organisationNumber = $organisationNumber;
        $organisation->goal = $goal;
        $organisation->location = $location;
        $organisation->members = $this->parseMembers($members);
        $organisation->roles = $this->parseRoles($roles);

        return $organisation;
    }


    public function get(string $uri):stdClass
    {
       /* try {
            $result = $this->client->get($uri, ['headers' => $this->generalHeaders]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/



    }
    public function delete(string $uri):ResponseInterface
    {
        try
        {
            $result = $this->client->delete($uri);
        }
        catch(RequestException $e)
        {
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        return $result;
    }
    public function postMember(string $uri,
                               string $firstname,
                               string $lastname,
                               string $email,
                               string $dateOfBirth,
                               string $userName,
                               string $passWord,
                               array $roles,
                               array $organisations,
                               bool $contributionPaid,
                               string $membershipEndDate,
                               ?array $tags
    )
    {
        $member = $this->parseMemberToCreate($firstname,
            $lastname,
            $email,
            $dateOfBirth,
            $userName,
            $passWord,
            $roles,
            $organisations,
            $contributionPaid,
            $membershipEndDate,
            $tags
        );

        $request = json_encode($member);

        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';

/*        try {
            $result = $this->client->post($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }
    public function putMember(string $uri,
                              string $firstname,
                              string $lastname,
                              string $email,
                              string $dateOfBirth,
                              string $userName,
                              string $passWord,
                              array $roles,
                              array $organisations,
                              ?bool $contributionPaid,
                              ?string $membershipEndDate,
                              ?array $tags
    )
    {

        $member = $this->parseMemberToCreate($firstname,
            $lastname,
            $email,
            $dateOfBirth,
            $userName,
            $passWord,
            $roles,
            $organisations,
            $contributionPaid,
            $membershipEndDate,
            $tags
        );

        $request = json_encode($member);

        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';

/*        try {
            $result = $this->client->put($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }

    public function patchMember(string $uri,
                                string $id,
                                ?string $firstname,
                                ?string $lastname,
                                ?string $email,
                                ?string $passWord,
                                ?array $roles,
                                ?array $organisations,
                                ?bool $contributionPaid,
                                ?string $membershipEndDate,
                                ?array $tags
    )
    {
        $member = (object)[];
        //$rolesArray = $this->parseRoles($roles);
        //$organisationsArray = $this->parseOrganisations($organisations)
        $member->id = $id;
        if($firstname)
            $member->firstName = $firstname;
        if($lastname)
            $member->lastName = $lastname;

        if($email)
        $member->email = $email;

        if($passWord)
        $member->passWord = $passWord;

        if($roles)
        $member->roles = $this->parseRoles($roles);
        if($organisations)
        $member->organisations = $this->parseOrganisations($organisations);

        if($contributionPaid)
            $member->contributionPaid = $contributionPaid;
        if($membershipEndDate)
            $member->membershipEndDate = $membershipEndDate;
        if($tags)
            $member->tags = $this->parseTags($tags);

        print_r($member);
        $request = json_encode($member);

        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';

/*        try {
            $result = $this->client->patch($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }
    public function postOrganisation(
        string $uri,
        string $name,
        int $organisationNumber,
        string $goal,
        stdClass $location,
        array $roles,
        array $members
    )
    {
        $organisation = $this->parseOrganisationToCreate($name, $organisationNumber, $goal, $location, $members, $roles);

        print_r($organisation);
        $request = json_encode($organisation);

        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';
        /*        try {
            $result = $this->client->post($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }
    public function putOrganisation(
        string $uri,
        string $name,
        int $organisationNumber,
        string $goal,
        stdClass $location,
        array $roles,
        array $members
    )
    {
        $organisation = $this->parseOrganisationToCreate($name, $organisationNumber, $goal, $location, $members, $roles);

        print_r($organisation);
        $request = json_encode($organisation);


        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';
        /*        try {
            $result = $this->client->put($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }
    public function patchOrganisation(
        string $uri,
        string $id,
        ?string $name,
        ?int $organisationNumber,
        ?string $goal,
        ?stdClass $location,
        ?array $roles,
        ?array $members
    )
    {
        $organisation = (object)[];

        $organisation->id = $id;
        if($name)
            $organisation->name = $name;
        if($organisationNumber)
            $organisation->organisationNumber = $organisationNumber;
        if($goal)
            $organisation->goal = $goal;
        if($location)
            $organisation->location = $location;
        if($roles)
            $organisation->roles = $this->parseRoles($roles);
        if($members)
            $organisation->members = $this->parseMembers($members);

        print_r($organisation);
        $request = json_encode($organisation);

        var_dump($request);
        $headers = $this->generalHeaders;
        $headers['Content-type'] = 'application/json';

/*        try {
            $result = $this->client->patch($uri, ['headers' => $headers, 'body' => $request]);
        }catch(RequestException $e){
            echo $e->getRequest() . "\n";
            if($e->hasResponse())
                echo $e->getResponse() ."\n";
            else
                echo "Network Error";
            return null;
        }
        if( $result->getStatusCode() == 200 && $result->getHeaderLine('Content-type') == "application/json")
            return json_decode($result->getBody());
        else{
            $response = (object)[];
            $response->statusCode = $result->getStatusCode();
            $response->reason = $result->getReasonPhrase();

            return $response;
        }*/
    }

}
