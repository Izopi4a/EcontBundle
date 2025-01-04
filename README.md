# EcontBundle

api docs by econt here: https://www.econt.com/developers/31-sazdavane-na-dokument-za-izprashtane-tovaritelnitsa.html

# Install
```shell
composer require izopi4a/econt-bundle
```

# Post install
add this to your enviorment variables
```shell
IZOPI4A_ECONT_USER=
IZOPI4A_ECONT_PASS=
IZOPI4A_ECONT_LOCALE=BG
IZOPI4A_ECONT_DEV=true
```

# Usage

#### It is a service, so you can autowire it. Example:

```php
#[Route('/{_locale<%app.supported_locales%>}', name: 'app_home')]
public function index(EcontService $econtService): Response
{

    $offices_in_sofia = $econtService->getOfficesInCity(41);
    
    dd($offices_in_sofia)
}
```

### Getting all the cities
I didn't find an option to filter the cities, so Econt are returning ~6k cities, in my case I store them in my database so I can use them faster.
Example entity / php bin/console make:entity City

```php
<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_bg = null;

    #[ORM\Column(length: 255)]
    private ?string $name_en = null;

    #[ORM\Column(nullable: true)]
    private ?int $econt_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $speedy_id = null;

    #[ORM\Column(length: 255)]
    private ?string $post_code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameBg(): ?string
    {
        return $this->name_bg;
    }

    public function setNameBg(string $name_bg): static
    {
        $this->name_bg = $name_bg;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): static
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function getEcontId(): ?int
    {
        return $this->econt_id;
    }

    public function setEcontId(?int $econt_id): static
    {
        $this->econt_id = $econt_id;

        return $this;
    }

    public function getSpeedyId(): ?int
    {
        return $this->speedy_id;
    }

    public function setSpeedyId(?int $speedy_id): static
    {
        $this->speedy_id = $speedy_id;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function setPostCode(string $post_code): static
    {
        $this->post_code = $post_code;

        return $this;
    }
}

```
and example command. Just use php bin/console to start the command. And then replace execute method

```php
protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $repo = $this->entityManager->getRepository(City::class);
        $inserted = 0;

        $items = $this->econtService->getAllCitiesInCountry();

        foreach ($items as $econtCity) {
            $exists = $repo->findOneBy(['econt_id' => $econtCity->getId()]);

            if (!$exists) {
                $city = new City();
                $city->setNameBg($econtCity->getName());
                $city->setNameEn($econtCity->getNameEn());
                $city->setEcontId($econtCity->getId());
                $city->setPostCode($econtCity->getPostCode());
                $this->entityManager->persist($city);
                $inserted++;
            }
        }

        $this->entityManager->flush();

        $io->success('Done. Inserted '.$inserted.' cities.');

        return Command::SUCCESS;
    }
```