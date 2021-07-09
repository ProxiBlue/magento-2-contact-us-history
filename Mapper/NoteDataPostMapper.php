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
    /**
     * @var NoteDataInterfaceFactory
     */
    private $noteInterfaceFactory;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param NoteDataInterfaceFactory $noteInterfaceFactory
     * @param DateTime $dateTime
     * @param Session $customerSession
     * @param RequestInterface $request
     */
    public function __construct(
        NoteDataInterfaceFactory $noteInterfaceFactory,
        DateTime $dateTime,
        Session $customerSession,
        RequestInterface $request
    ) {
        $this->noteInterfaceFactory = $noteInterfaceFactory;
        $this->dateTime = $dateTime;
        $this->customerSession = $customerSession;
        $this->request = $request;
    }

    /**
     * Map data model
     *
     * @return NoteDataInterface
     */
    public function map(): NoteDataInterface
    {
        $params = $this->request->getParams();
        /** @var NoteDataInterface $noteDataObject */
        $noteDataObject = $this->noteInterfaceFactory->create(
            [
                'data' => [
                    NoteDataInterface::EMAIL => $params['email'],
                    NoteDataInterface::CONTACT_NAME => $params['name'],
                    NoteDataInterface::PHONE => $params['telephone'],
                    NoteDataInterface::MESSAGE => $params['comment'],
                    NoteDataInterface::CUSTOMER_ID => $this->customerSession->getCustomerId(),
                    NoteDataInterface::FORM_ID => $params['form_id'],
                    NoteDataInterface::FORM_DATA => $params['form_data'] = $params,
                ]
            ]
        );

        return $noteDataObject;
    }
}
