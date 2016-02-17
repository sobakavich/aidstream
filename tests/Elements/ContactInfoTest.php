<?php namespace Test\Elements;

use App\Migration\Elements\ContactInfo;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\ContactInfoDataProvider;

class ContactInfoTest extends AidStreamTestCase
{
    use ContactInfoDataProvider;

    protected $contactInfo;

    protected $typeCode;
    protected $contactOrgNarrative;
    protected $contactInfoDepartmentNarrative;
    protected $contactInfoPersonNarrative;
    protected $contactInfoJobNarrative;
    protected $telephone;
    protected $email;
    protected $website;
    protected $mailingAddress;

    protected $expectedOutput;


    public function setUp()
    {
        parent::setUp();
        $this->contactInfo = new ContactInfo();
    }

    /** {@test} */
    public function itShouldFormatContactInfo()
    {
        $this->initializeTestData();

        $this->expectedOutput = $this->formatContactInfo();

        $this->assertEquals(
            $this->expectedOutput,
            $this->contactInfo->format(
                $this->typeCode,
                $this->contactOrgNarrative,
                $this->contactInfoDepartmentNarrative,
                $this->contactInfoPersonNarrative,
                $this->contactInfoJobNarrative,
                $this->telephone,
                $this->email,
                $this->website,
                $this->mailingAddress
            )
        );
    }
    
    /** {@test} */
    public function itShouldFormatEmptyContactInfo()
    {
        $this->initializeEmptyTestData();

        $this->expectedOutput = $this->formatContactInfo();

        $this->assertEquals(
            $this->expectedOutput,
            $this->contactInfo->format(
                $this->typeCode,
                $this->contactOrgNarrative,
                $this->contactInfoDepartmentNarrative,
                $this->contactInfoPersonNarrative,
                $this->contactInfoJobNarrative,
                $this->telephone,
                $this->email,
                $this->website,
                $this->mailingAddress
            )
        );
    }

    /** {@test} */
    public function itShouldFormatContactInfoWithOnlySomeEmptyFields()
    {
        $this->initializeSomeTestData();
        $this->expectedOutput = $this->formatContactInfo();

        $this->assertEquals(
            $this->expectedOutput,
            $this->contactInfo->format(
                $this->typeCode,
                $this->contactOrgNarrative,
                $this->contactInfoDepartmentNarrative,
                $this->contactInfoPersonNarrative,
                $this->contactInfoJobNarrative,
                $this->telephone,
                $this->email,
                $this->website,
                $this->mailingAddress
            )
        );

    }

    /**
     * Format ContactInfo according the Template provided.
     * @return mixed
     */
    protected function formatContactInfo()
    {
        $template = getHeaders('ActivityData', 'contactInfo')[0];

        $template['type']                         = $this->typeCode;
        $template['organization'][0]['narrative'] = $this->contactOrgNarrative;
        $template['department'][0]['narrative']   = $this->contactInfoDepartmentNarrative;
        $template['person_name'][0]['narrative']  = $this->contactInfoPersonNarrative;
        $template['job_title'][0]['narrative']    = $this->contactInfoJobNarrative;
        $template['telephone']                    = $this->telephone;
        $template['email']                        = $this->email;
        $template['website']                      = $this->website;
        $template['mailing_address']              = $this->mailingAddress;

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
