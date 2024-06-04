<?php

declare(strict_types=1);

namespace Generator\Generators\Message;

use Generator\PHP\AnnotationsParser;
use Generator\PHP\Property\TypeFactory;
use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\Message;

final readonly class MessageClassParser
{
    public function __construct(
        private AnnotationsParser $annotationsParser = new AnnotationsParser(),
    ) {
    }

    /**
     * @param class-string<Message> $class
     * @throws \ReflectionException
     */
    public function parse(string $class): MessageClass
    {
        $reflection = new \ReflectionClass($class);

        if (!$reflection->isSubclassOf(Message::class)) {
            throw new \InvalidArgumentException(\sprintf('Class %s is not a subclass of %s', $class, Message::class));
        }

        new $class;
        $pool = DescriptorPool::getGeneratedPool();
        $descriptor = $pool->getDescriptorByClassName($class);

        $method = $reflection->getMethod('__construct');

        $parser = new MessageCommentsParser(new TypeFactory());
        $properties = $parser->parse($descriptor, $method);

        return new MessageClass(
            $class,
            $properties,
            $this->annotationsParser->parseFromClass($reflection),
        );
    }
}
