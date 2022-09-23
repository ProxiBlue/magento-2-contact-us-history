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
     */
    public function getNoteId(): ?int;

    /**
     * Set note id
     *
     */
    public function setNoteId(int $noteId): void;

    /**
     * Get note email
     *
     */
    public function getEmail(): ?string;

    /**
     * Set note email
     *
     */
    public function setEmail(string $email): void;

    /**
     * Get note contact name
     *
     */
    public function getContactName(): ?string;

    /**
     * Set note contact name
     *
     */
    public function setContactName(string $contactName): void;

    /**
     * Get note message
     *
     */
    public function getMessage(): ?string;

    /**
     * Set note message
     *
     */
    public function setMessage(string $message): void;

    /**
     * Get note phone number
     *
     */
    public function getPhone(): ?string;

    /**
     * Set note phone number
     *
     */
    public function setPhone(?string $phone):void;

    /**
     * Get created date
     *
     */
    public function getCreatedDate(): ?string;

    /**
     * Set created date
     *
     */
    public function setCreatedDate(string $createdDate): void;

    /**
     * Get form id
     *
     */
    public function getFormId(): ?string;

    /**
     * Set form id
     *
     */
    public function setFormId(string $formId): void;

    /**
     * Get form id
     *
     */
    public function getFormData(): ?string;

    /**
     * Set form id
     *
     */
    public function setFormData(string $formId): void;


    /**
     * Get customer id
     *
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer id
     *
     */
    public function setCustomerId(?int $customerId): void;
}
