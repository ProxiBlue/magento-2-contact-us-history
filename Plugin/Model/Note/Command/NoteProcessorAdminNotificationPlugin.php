<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Plugin\Model\Note\Command;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Notification\NotifierInterface as NotifierPool;
use VitaliyBoyko\ContactUsHistory\Service\ProcessNoteService;

/**
 * Class provides admin notification after new note placed
 */
class NoteProcessorAdminNotificationPlugin
{
    public function __construct(private readonly NotifierPool $notifier, private readonly RequestInterface $request)
    {
    }

    /**
     * @return void
     */
    public function afterExecute(ProcessNoteService $subject)
    {
        $customerName = $this->request->getParam('name');
        if(!empty($customerName)) {
            $this->notifier->addNotice(
                __('New note from contact form'),
                sprintf(__('Customer %s left note in contact form'), $customerName)
            );
        }
    }
}
