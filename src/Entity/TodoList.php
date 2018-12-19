<?php
# php bin/console doctrine:migrations:diff
# php bin/console doctrine:migrations:migrate

namespace App\Entity;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoListRepository")
 */
class TodoList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ready;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getReady(): ?bool
    {
        return $this->ready;
    }

    public function setReady(bool $ready): self
    {
        $this->ready = $ready;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function jsonDecode($request)
    {
        return json_decode($request->getContent(), true);
    }

    public function serialize($items)
    {
        $arrayCollection = array();

        foreach($items as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'text' => $item->getText(),
                'ready' => $item->getReady()
            );
        }

        return $arrayCollection;
    }

    static function validate(array $data)
    {
        $validator = Validation::createValidator();
        $errors = [];

        if (!isset($data['ready'])) {
            throw new BadRequestHttpException('Ready shouldn`t be empty.');
        }
        if (!isset($data['text'])) {
            throw new BadRequestHttpException('Text shouldn`t be empty.');
        }
        
        $stateError = $validator->validate($data['ready'], [
            new Assert\Type([
                'type' => 'boolean',
                'message' => 'The value {{ value }} is not a valid {{ type }}.'
            ])
        ]);

        $messageError = $validator->validate($data['text'], [
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'The value {{ value }} is not a valid {{ type }}.'
            ]),
            new Assert\Length([
                'min' => 1,
                'max' => 236,
                'exactMessage' => 'The value {{ value }} must be 16 characters long!'
            ])
        ]);

        if(count($stateError) > 0 || count($messageError) > 0) {
            return true;
        }

        return false;
    }
}
