<?php
/**
 * @author Vitaliy Boyko <vitaliyboyko@i.ua>
 */
declare(strict_types=1);

namespace VitaliyBoyko\ContactUsHistory\Api\Data;

/**
 * Note DTO
 * @api
 */
interface NoteDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    final const NOTE_ID = 'note_id';
    final const CONTACT_NAME = 'contact_name';
    final const EMAIL = 'email';
    final const MESSAGE = 'message';
    final const PHONE = 'phone';
    final const CREATED_DATE = 'created_date';
    final const CUSTOMER_ID = 'customer_id';
    final const FORM_ID = 'form_id';
    final const FORM_DATA = 'form_data';
    /**#@-*/
    /**
     * Get note id
     *
     * @return null|int
     */
    public function getNoteId(): ?int;

    /**
     * Set note id
     *
     * @param int $noteId
     * @return void
     */
    public function setNoteId(int $noteId): void;

    /**
     * Get note email
     *
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * Set note email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void;

    /**
     * Get note contact name
     *
     * @return null|string
     */
    public function getContactName(): ?string;

    /**
     * Set note contact name
     *
     * @param string $contactName
     * @return void
     */
    public function setContactName(string $contactName): void;

    /**
     * Get note message
     *
     * @return null|string
     */
    public function getMessage(): ?string;

    /**
     * Set note message
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;

    /**
     * Get note phone number
     *
     * @return null|string
     */
    public function getPhone(): ?string;

    /**
     * Set note phone number
     *
     * @param string|null $phone
     * @return void
     */
    public function setPhone(?string $phone):void;

    /**
     * Get created date
     *
     * @return null|string
     */
    public function getCreatedDate(): ?string;

    /**
     * Set created date
     *
     * @param string $createdDate
     * @return void
     */
    public function setCreatedDate(string $createdDate): void;

    /**
     * Get form id
     *
     * @return null|string
     */
    public function getFormId(): ?string;

    /**
     * Set form id
     *
     * @param string $formId
     * @return void
     */
    public function setFormId(string $formId): void;

    /**
     * Get form id
     *
     * @return null|string
     */
    public function getFormData(): ?string;

    /**
     * Set form id
     *
     * @param string $formId
     * @return void
     */
    public function setFormData(string $formId): void;


    /**
     * Get customer id
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer id
     *
     * @param int|null $customerId
     * @return void
     */
    public function setCustomerId(?int $customerId): void;
}
