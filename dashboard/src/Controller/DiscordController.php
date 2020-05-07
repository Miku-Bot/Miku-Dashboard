<?php
namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DiscordController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/discord", name="connect_discord_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('discord') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
	    	'identify', 'email', 'guilds', 'guilds.join' // the scopes you want to access
            ])
        ;
	}

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/discord/bot", name="connect_discord_bot_start")
     */
    public function connectActionBot(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('discord') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
	    	'identify', 'email', 'guilds', 'guilds.join', 'bot' // the scopes you want to access
            ], [8])
        ;
	}

    /**
     * After going to Discord, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/discord/check", name="connect_discord_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\DiscordClient $client */
        $client = $clientRegistry->getClient('discord');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\DiscordUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            echo '<h2>Resource owner details:</h2>';
            printf('Hello %s#%s!<br/><br/>', $user->getUsername(), $user->getDiscriminator());
            var_export($user->toArray());
            die();
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
}