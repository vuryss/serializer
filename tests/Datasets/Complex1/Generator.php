<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

use Faker\Generator as FakerGenerator;

use function Pest\Faker\fake;

readonly class Generator
{
    public function generate(): RouteData
    {
        $faker = fake();

        return new RouteData(
            personnel: $this->generatePersonnel($faker, $faker->numberBetween(2, 5)),
            locations: $this->generateLocationDetails($faker, $faker->numberBetween(30, 50)),
            clients: $this->generateClientDetails($faker, $faker->numberBetween(30, 50)),
            assets: $this->generateAssetDetails($faker, $faker->numberBetween(1, 3)),
            shipments: $this->generateShipmentDetails($faker, $faker->numberBetween(30, 50)),
            routeExternalId: $faker->uuid(),
            branchExternalId: $faker->uuid(),
            startTime: \DateTimeImmutable::createFromInterface($faker->dateTime()),
            endTime: \DateTimeImmutable::createFromInterface($faker->dateTime()),
            startLocationExternalId: $faker->uuid(),
            endLocationExternalId: $faker->uuid(),
            fixedSequence: $faker->boolean(),
            nation: $faker->countryCode(),
            routeName: $faker->sentence(3),
            interruptionExternalId: $faker->uuid(),
            facilities: $this->generateFacilityDetails($faker, $faker->numberBetween(1, 3)),
            masterRoutes: $this->generateMasterRoutes($faker, $faker->numberBetween(10, 20)),
            supplementaryData: $this->generateSupplementaryData($faker, $faker->numberBetween(0, 5)),
            routeRecords: $this->generateRecordInstances($faker, $faker->numberBetween(0, 3)),
            dataFiles: $this->generateDataFiles($faker, $faker->numberBetween(0, 3)),
        );
    }

    /**
     * @return PersonnelData[]
     */
    private function generatePersonnel(FakerGenerator $faker, int $count): array
    {
        $personnel = [];
        for ($i = 0; $i < $count; $i++) {
            $personnel[] = new PersonnelData(
                personnelExternalId: $faker->uuid(),
                role: $faker->randomElement(['DRIVER', 'CO_DRIVER']),
                givenName: $faker->firstName(),
                familyName: $faker->lastName(),
            );
        }
        return $personnel;
    }

    /**
     * @return LocationDetails[]
     */
    private function generateLocationDetails(FakerGenerator $faker, int $count): array
    {
        $locations = [];
        for ($i = 0; $i < $count; $i++) {
            $locations[] = new LocationDetails(
                nation: $faker->countryCode(),
                zipCode: $faker->postcode(),
                municipality: $faker->city(),
                region: $faker->boolean() ? $faker->citySuffix() : '',
                thoroughfare: $faker->streetName(),
                buildingIdentifier: $faker->buildingNumber(),
                contactNumber: $faker->phoneNumber(),
                province: $faker->state(),
                locationExternalId: $faker->uuid(),
                locationName: $faker->company(),
            );
        }
        return $locations;
    }

    /**
     * @return ClientDetails[]
     */
    private function generateClientDetails(FakerGenerator $faker, int $count): array
    {
        $clients = [];
        for ($i = 0; $i < $count; $i++) {
            $clients[] = new ClientDetails(
                clientName: $faker->company(),
                clientExternalId: $faker->uuid(),
            );
        }
        return $clients;
    }

    /**
     * @return AssetDetails[]
     */
    private function generateAssetDetails(FakerGenerator $faker, int $count): array
    {
        $assets = [];
        for ($i = 0; $i < $count; $i++) {
            $assets[] = new AssetDetails(
                assetExternalId: $faker->uuid(),
                assetCategory: $faker->word(),
                dimensionHeight: $faker->numberBetween(100, 500),
                dimensionLength: $faker->numberBetween(200, 1000),
                dimensionWidth: $faker->numberBetween(100, 300),
                mass: $faker->numberBetween(1000, 5000),
                minCapacity: $faker->numberBetween(500, 2000),
                maxCapacity: $faker->numberBetween(100, 500),
                grossVehicleWeightRating: $faker->numberBetween(5000, 20000),
                maxAxleRating: $faker->numberBetween(2000, 10000),
                registrationIdentifier: $faker->regexify('[A-Z]{2}-[A-Z0-9]{2,5}'),
                attachmentMethod: $faker->word(),
            );
        }
        return $assets;
    }

    /**
     * @return ShipmentDetails[]
     */
    private function generateShipmentDetails(FakerGenerator $faker, int $count): array
    {
        $shipments = [];
        for ($i = 0; $i < $count; $i++) {
            $shipments[] = new ShipmentDetails(
                clientExternalId: $faker->uuid(),
                supplementaryData: $this->generateSupplementaryData($faker, $faker->numberBetween(5, 10)),
                shipmentWaypoints: $this->generateShipmentWaypoints($faker, $faker->numberBetween(10, 20)),
                activityType: $faker->randomElement(ShipmentActivityType::cases()),
                sequenceNumber: $i + 1,
                shipmentExternalId: $faker->uuid(),
                locationExternalId: $faker->uuid(),
                agreementNumber: $faker->boolean() ? $faker->numerify('AGRMT-#####') : null,
                trackingNumber: $faker->boolean() ? $faker->numerify('TRACK-#####') : null,
                orderReference: $faker->boolean() ? $faker->numerify('REF-#####') : null,
                shipmentRecords: $this->generateRecordInstances($faker, $faker->numberBetween(2, 5)),
                dataFiles: $this->generateDataFiles($faker, $faker->numberBetween(2, 5)),
                itemExternalId: $faker->boolean() ? $faker->uuid() : null,
            );
        }
        return $shipments;
    }

    /**
     * @return ShipmentWaypoint[]
     */
    private function generateShipmentWaypoints(FakerGenerator $faker, int $count): array
    {
        $waypoints = [];
        for ($i = 0; $i < $count; $i++) {
            $waypoints[] = new ShipmentWaypoint(
                waypointType: $faker->randomElement(JobGroupType::cases()),
                locationExternalId: $faker->uuid(),
                supplementaryData: $this->generateSupplementaryData($faker, $faker->numberBetween(0, 3)),
                jobActivities: $this->generateJobActivities($faker, $faker->numberBetween(1, 4)),
            );
        }
        return $waypoints;
    }

    /**
     * @return JobActivity[]
     */
    private function generateJobActivities(FakerGenerator $faker, int $count): array
    {
        $jobActivities = [];
        for ($i = 0; $i < $count; $i++) {
            $jobActivities[] = new JobActivity(
                activityCategory: $faker->word(),
                activityName: $faker->boolean() ? $faker->sentence(2) : null,
                components: $this->generateComponentData($faker, $faker->numberBetween(1, 3)),
                jobExternalId: $faker->uuid(),
            );
        }
        return $jobActivities;
    }

    /**
     * @return ComponentData[]
     */
    private function generateComponentData(FakerGenerator $faker, int $count): array
    {
        $components = [];
        for ($i = 0; $i < $count; $i++) {
            $componentOptions = [];
            $optionCount = $faker->numberBetween(1, 3);
            for ($j = 0; $j < $optionCount; $j++) {
                $componentOptions[] = new ComponentOption(
                    optionName: $faker->word(),
                    originSystemId: $faker->boolean() ? $faker->uuid() : null,
                );
            }

            $components[] = new ComponentData(
                refType: $faker->word(),
                targetValue: $faker->boolean() ? $faker->word() : null,
                componentOptions: $componentOptions,
            );
        }
        return $components;
    }

    /**
     * @return SupplementaryData[]
     */
    private function generateSupplementaryData(FakerGenerator $faker, int $count): array
    {
        $supplementary = [];
        for ($i = 0; $i < $count; $i++) {
            $supplementary[] = new SupplementaryData(
                orderIndex: $i + 1,
                details: $faker->sentence(),
                displayOnMain: $faker->boolean(),
                emphasized: $faker->boolean(),
                symbol: $faker->word(), // Assuming symbol is a string identifier like 'warning', 'info' etc.
            );
        }
        return $supplementary;
    }

    /**
     * @return RecordInstance[]
     */
    private function generateRecordInstances(FakerGenerator $faker, int $count): array
    {
        $records = [];
        for ($i = 0; $i < $count; $i++) {
            $records[] = new RecordInstance(
                recordType: $faker->randomElement(RecordType::cases()),
                serviceItems: $this->generateServiceItems($faker, $faker->numberBetween(0, 2)),
                textBlocks: $this->generateRecordTextBlocks($faker, $faker->numberBetween(1, 5)),
                recordName: $faker->boolean() ? $faker->sentence(3) : null,
            );
        }
        return $records;
    }

    /**
     * @return ServiceItem[]
     */
    private function generateServiceItems(FakerGenerator $faker, int $count): array
    {
        $items = [];
        for ($i = 0; $i < $count; $i++) {
            $items[] = new ServiceItem(
                itemNumber: $faker->numerify('ITEM-######'),
                description: $faker->sentence(),
                jobExternalId: $faker->boolean() ? $faker->uuid() : null,
                templateField: $faker->randomElement(ServiceItemTemplateField::cases()),
                measurementUnit: $faker->boolean() ? $faker->word() : null,
                referenceType: $faker->randomElement(['itemQuantity', 'otherValue']), // Example values
            );
        }
        return $items;
    }

    /**
     * @return RecordTextBlock[]
     */
    private function generateRecordTextBlocks(FakerGenerator $faker, int $count): array
    {
        $blocks = [];
        for ($i = 0; $i < $count; $i++) {
            $blocks[] = new RecordTextBlock(
                identifier: $faker->uuid(),
                content: $faker->paragraph(),
            );
        }
        return $blocks;
    }

    /**
     * @return DataFile[]
     */
    private function generateDataFiles(FakerGenerator $faker, int $count): array
    {
        $files = [];
        for ($i = 0; $i < $count; $i++) {
            $files[] = new DataFile(
                fileName: $faker->word() . '.' . $faker->fileExtension(),
                fileIdentifier: $faker->uuid(),
                dataSource: $faker->randomElement(DataSourceOrigin::cases()),
                fileFormat: $faker->randomElement(DataFileFormat::cases()),
            );
        }
        return $files;
    }

    /**
     * @return FacilityDetails[]
     */
    private function generateFacilityDetails(FakerGenerator $faker, int $count): array
    {
        $facilities = [];
        for ($i = 0; $i < $count; $i++) {
            $resourceOptions = [];
            $optionCount = $faker->numberBetween(0, 3);
            for ($j = 0; $j < $optionCount; $j++) {
                $option = new ResourceOption();
                $option->optionIdentifier = $faker->word();
                $option->originId = $faker->uuid();
                $resourceOptions[] = $option;
            }

            $facilities[] = new FacilityDetails(
                locationExternalId: $faker->uuid(),
                requiresWeighting: $faker->boolean(),
                facilityExternalId: $faker->uuid(),
                facilityName: $faker->company() . ' Facility',
                resourceOptions: $resourceOptions,
            );
        }
        return $facilities;
    }

    /**
     * @return string[]
     */
    private function generateMasterRoutes(FakerGenerator $faker, int $count): array
    {
        $masterRoutes = [];
        for ($i = 0; $i < $count; $i++) {
            $masterRoutes[] = $faker->uuid();
        }
        return $masterRoutes;
    }
}
