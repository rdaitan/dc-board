<?php
class AppException extends Exception
{
}

class ValidationException extends Exception
{
}

class RecordNotFoundException extends AppException
{
}

class PageNotFoundException extends AppException
{
}

class DuplicateEntryException extends AppException
{
}

class PermissionException extends AppException
{
}
