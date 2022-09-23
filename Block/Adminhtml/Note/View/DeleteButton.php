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
    /**
     * @var GetNoteByIdInterface
     */
    protected $getNoteById;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param GetNoteByIdInterface $getNoteById
     * @param RequestInterface $request
     * @param UrlInterface $url
     */
    public function __construct(
        GetNoteByIdInterface $getNoteById,
        RequestInterface $request,
        UrlInterface $url
    ) {
        $this->getNoteById = $getNoteById;
        $this->request = $request;
        $this->url = $url;
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

    /**
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['note_id' => $this->getNoteId()]);
    }

    /**
     * Return note Id
     *
     * @return int|null
     */
    private function getNoteId(): ?int
    {
        $noteId = (int)$this->request->getParam(NoteDataInterface::NOTE_ID);
        /** @var NoteDataInterface $note */
        if ($noteId) {
            try {
                $note = $this->getNoteById->execute($noteId);
                return $note->getNoteId();
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    private function getUrl(string $route = '', array $params = []): string
    {
        return $this->url->getUrl($route, $params);
    }
}
