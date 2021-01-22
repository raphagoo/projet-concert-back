<?php


namespace App\Helpers;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerHelper
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function prepareResponse($toSerialize){
        $jsonContent = $this->serializer->serialize($toSerialize, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            },
            'enable_max_depth' => true
        ]);

        $response = new Response($jsonContent);

        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

    public function normalizeObject($toNormalize, $objectClass){
        return $this->serializer->normalize($toNormalize, null, array(ObjectNormalizer::ENABLE_MAX_DEPTH => true));
    }

    public function deserializeRequest($jsonObject, $objectClass, $objectToPopulate){
        return $this->serializer->deserialize($jsonObject, $objectClass, 'json', ['object_to_populate' => $objectToPopulate, 'deep_object_to_populate' => true]);
    }
}
