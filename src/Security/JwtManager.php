<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class JwtManager
 * @package App\Security
 */
class JwtManager
{
    const KEY = 'Czx6vqZAIobtGo4YNzaOi59xQe3ZHWpNuB0zj4RmPA2B7dTcr1ZybdDMEbfp';
    const DURATION = 86400;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * JwtManager constructor.
     *
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return array
     */
    public function accessToken(string $email, string $password): array
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user and $this->passwordEncoder->isPasswordValid($user, $password)) {
            $now = time();

            return [
                "access_token" => JWT::encode([
                    "iss"  => "illusa",
                    "iat"  => $now,
                    "exp"  => $now + self::DURATION,
                    "user" => $user
                ], self::KEY),
                "expires_in"   => self::DURATION,
                "token_type"   => "bearer",
                "scope"        => null,
            ];
        }
        throw new UsernameNotFoundException();
    }

    /**
     * @param string $jwt
     *
     * @return User
     * @throws Exception
     */
    public function user(string $jwt): User
    {
        $decoded = JWT::decode($jwt, self::KEY, ['HS256']);
        if (!isset($decoded->user)) {
            throw new Exception("Invalid JWT token");
        }

        $user = $this->userRepository->find($decoded->user->id);
        if (!$user) {
            throw new Exception("Unexistent user");
        }

        return $user;
    }
}
