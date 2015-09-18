<?php
class AppException extends Exception
{
}

class ValidationException extends Exception
{
}

// Thrown if a thread, comment, user, or follow does not exist in the database
class RecordNotFoundException extends AppException
{
}

class PageNotFoundException extends AppException
{
}

class DuplicateEntryException extends AppException
{
}

// Thrown if user cannot do the action
class PermissionException extends AppException
{
}

// Thrown if a thread category does not exist
class InvalidCategoryException extends AppException
{
}
