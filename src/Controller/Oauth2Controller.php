<?php

namespace App\Controller;

use App\Security\JwtManager;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class Oauth2Controller
 * @package App\Controller
 */
class Oauth2Controller extends AbstractController
{
    public function postToken(Request $request, JwtManager $jwtManager): JsonResponse
    {
        switch ($request->request->getAlnum('grant_type')) {
            case 'password':
                return $this->json($jwtManager->accessToken(
                    $request->request->get('username'),
                    $request->request->get('password')
                ));

                break;
            default:
                throw $this->createAccessDeniedException("Unsupported grant_type");
        }
    }
}
