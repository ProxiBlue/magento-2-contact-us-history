<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Mapper;

use Magento\Framework\App\RequestInterface;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterfaceFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\DateTime\DateTime;

class NoteDataPostMapper
{
    public function __construct(private readonly NoteDataInterfaceFactory $noteInterfaceFactory, private readonly DateTime $dateTime, private readonly Session $customerSession, private readonly RequestInterface $request)
    {
    }

    /**
     * Map data model
     *
     */
    public function map(): NoteDataInterface
    {
        $params = $this->request->getParams();
        /** @var NoteDataInterface $noteDataObject */
        $noteDataObject = $this->noteInterfaceFactory->create(
            [
                'data' => [
                    NoteDataInterface::EMAIL => $params['email'],
                    NoteDataInterface::CONTACT_NAME => $params['name'] ?? 'Unknown',
                    NoteDataInterface::PHONE => $params['telephone'] ?? 'Unknown',
                    NoteDataInterface::MESSAGE => $params['comment'],
                    NoteDataInterface::CUSTOMER_ID => $this->customerSession->getCustomerId(),
                    NoteDataInterface::FORM_ID => $params['form_id'] ?? '',
                    NoteDataInterface::FORM_DATA => $params['form_data'] = $params,
                ]
            ]
        );

        return $noteDataObject;
    }
}
