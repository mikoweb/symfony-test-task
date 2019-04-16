<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Serializer\CollectionSerializer;
use App\Serializer\UserDeserializer;
use App\Serializer\UserSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ApiUsersController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(UserRepository $repository, ValidatorInterface $valiadator)
    {
        $this->repository = $repository;
        $this->validator = $valiadator;
    }

    public function index(): JsonResponse
    {
        return new JsonResponse((new CollectionSerializer(
            $this->repository->findAll(),
            UserSerializer::class
        ))->serialize());
    }

    public function read(User $user): JsonResponse
    {
        return new JsonResponse((new UserSerializer($user))->serialize());
    }

    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = new User();
        $deserializer = (new UserDeserializer($user));
        $deserializer->create(json_decode($request->getContent(), true));
        $valid = $this->validator->validate($user, null, ['Default', 'api_create'])->count() === 0;

        if ($valid) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->eraseCredentials();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return new JsonResponse([
            'valid' => $valid,
            'user' => (new UserSerializer($user))->serialize()
        ]);
    }

    public function update(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $deserializer = (new UserDeserializer($user));
        $deserializer->update(json_decode($request->getContent(), true));
        $valid = $this->validator->validate($user)->count() === 0;

        if ($valid) {
            if (!empty($user->getPlainPassword())) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->eraseCredentials();
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return new JsonResponse([
            'valid' => $valid,
            'user' => (new UserSerializer($user))->serialize()
        ]);
    }

    public function delete(User $user): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse((new UserSerializer($user))->serialize());
    }
}
