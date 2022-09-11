<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Block\Adminhtml\Note\View;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use VitaliyBoyko\ContactUsHistory\Api\Data\NoteDataInterface;
use VitaliyBoyko\ContactUsHistory\Api\Query\GetNoteByIdInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * @inheritdoc
 */
class DeleteButton implements ButtonProviderInterface
{
    public function __construct(protected GetNoteByIdInterface $getNoteById, private readonly RequestInterface $request, private readonly UrlInterface $url)
    {
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getNoteId()) {
            $data = [
                'label' => __('Delete Note'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' .
                    __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 30,
            ];
        }

        return $data;
    }

    private function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['note_id' => $this->getNoteId()]);
    }

    /**
     * Return note Id
     */
    private function getNoteId(): ?int
    {
        $noteId = (int)$this->request->getParam(NoteDataInterface::NOTE_ID);
        /** @var NoteDataInterface $note */
        if ($noteId) {
            try {
                $note = $this->getNoteById->execute($noteId);
                return $note->getNoteId();
            } catch (NoSuchEntityException) {
                return null;
            }
        }

        return null;
    }

    /**
     * Generate url by route and parameters
     */
    private function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
