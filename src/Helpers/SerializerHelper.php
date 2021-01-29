<?php


namespace App\Helpers;


use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerHelper
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function prepareResponse($toSerialize, $groups){
        $jsonContent = $this->serializer->serialize($toSerialize, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            },
            'groups' => $groups
        ]);

        $response = new Response($jsonContent);

        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

    public function deserializeRequest($jsonObject, $objectClass, $objectToPopulate){
        return $this->serializer->deserialize($jsonObject, $objectClass, 'json', ['object_to_populate' => $objectToPopulate, 'deep_object_to_populate' => true]);
    }
}
