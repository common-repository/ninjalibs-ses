<?php

namespace NinjaLibs\Ses;

use Aws\Ses\SesClient;

class Utils
{
    private static $SESClient=null;

    /**
     * @return SesClient
     */
    public static function getSesClient()
    {
        if (self::$SESClient == null) {
            self::$SESClient = new SesClient([
                // 'profile' => 'default',
                'version' => 'latest',
                'region' => NINJALIBS_SES_AWS_REGION,
                'credentials' => [
                    'key' => NINJALIBS_SES_AWS_ACCESS_KEY_ID,
                    'secret' => NINJALIBS_SES_AWS_ACCESS_KEY_SECRET
                ]
            ]);
        }

        return self::$SESClient;
    }

    public static function getIdentityVerificationAttributes()
    {
        $emails = self::getEmails();
        if (!$emails) {
            return null;
        }

        $identities =['Identities'=> $emails ];
        $response =  self::getSesClient()->getIdentityVerificationAttributes($identities);
        if (isset($response['VerificationAttributes']) && is_array($response['VerificationAttributes'])) {
            return $response['VerificationAttributes'];
        }
        return null;
    }


    public static function getIdentityVerificationAttributesDomains()
    {
        $domains = self::getDomains();
        if (!$domains) {
            return null;
        }

        $identities =['Identities'=> $domains ];
        $response =  self::getSesClient()->getIdentityVerificationAttributes($identities);
        if (isset($response['VerificationAttributes']) && is_array($response['VerificationAttributes'])) {
            return $response['VerificationAttributes'];
        }
        return null;
    }

    public static function getVerifiedEmails()
    {
        $identites_with_attr = self::getIdentityVerificationAttributes();
        $verified_emails = [];

        if (!$identites_with_attr) {
            return null;
        }
      
        foreach ($identites_with_attr as $email => $attr) {
            if ($attr['VerificationStatus'] == "Success") {
                $verified_emails[] = $email;
            }
        }

        return $verified_emails;
    }

    public static function getEmails()
    {
        $client = self::getSesClient();
        $result = $client->listIdentities([
            'IdentityType' => 'EmailAddress',
            'MaxItems' => 100,
        ]);

        //return $result;

        if (isset($result['Identities']) && is_array($result['Identities'])) {
            $emails = array_filter($result['Identities'], Utils::class.'::Matches');
            return array_values($emails);
        }
        
        return null;
    }

    public static function getDomains()
    {
        $client = self::getSesClient();
        $result = $client->listIdentities([
            'IdentityType' => 'Domain',
            'MaxItems' => 100,
        ]);

        if (isset($result['Identities']) && is_array($result['Identities'])) {
            $domains = array_filter($result['Identities'], Utils::class.'::Matches');
            return array_values($domains);
        }
        
        return null;
    }

    public static function getSendStatistics()
    {
        return self::getSesClient()->getSendStatistics([]);
    }

    public static function Matches($domain_or_email)
    {
        if (!defined('NINJALIBS_SES_DOMAIN_MATCH') || NINJALIBS_SES_DOMAIN_MATCH == "*") {
            return true;
        }

        $domain = $domain_or_email;
        if (strpos($domain_or_email, '@')) {
            list($user, $domain) = explode('@', $domain_or_email);
        }

        //convert to regex
        return preg_match(NINJALIBS_SES_DOMAIN_MATCH, $domain);
    }
}
