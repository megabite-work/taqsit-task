<?php

namespace App\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CustomResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(MapRequestPayload::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? null;

        if (empty($attribute) && $attribute?->resolver !== static::class) {
            return [];
        }

        $class = $argument->getType();
        $validationFailedCode = $attribute->validationFailedStatusCode;
        $payload = array_merge($request->getPayload()->all(), $request->query->all(), $request->files->all(), $request->attributes->all());
        $constraints = $argument->constraints ?? null;
        
        if (!empty($constraints) && !$constraints instanceof Assert\All) {
            $constraints = new Assert\All($constraints);
        }

        $dto = new $class();
        
        foreach ($payload as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }
        
        $violations = $this->validator->validate($dto, $constraints, $attribute->validationGroups ?? null);
        
        if ($violations->count()) {
            throw HttpException::fromStatusCode($validationFailedCode, implode("\n", array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))), new ValidationFailedException($dto, $violations));
        }

        yield $dto;
    }
}
