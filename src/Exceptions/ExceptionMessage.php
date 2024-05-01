<?php


namespace Volosyuk\MilvusPhp\Exceptions;


class ExceptionMessage
{
    const AUTO_ID_FIELD_TYPE = "The auto_id can only be specified on field with DataType.INT64.";
    const AUTO_ID_ONLY_ON_PK = "The auto_id can only be specified on the primary key field.";
    const AUTO_ID_TYPE = "Param auto_id must be bool type.";
    const COLLECTION_ENTITY_FIELD_NUM_MISMATCH = "Collection fields num (%s) and entity fields num (%s) mismatch";
    const COLLECTION_NOT_EXIST_NO_SCHEMA = "Collection %s not exist, or you can pass in schema to create one.";
    const CONSISTENCY_LEVEL_INCONSISTENT = "The parameter consistency_level is inconsistent with that of existed collection.";
    const DATA_LENGTHS_INCONSISTENT = "Arrays must all be same length.";
    const FIELD_D_TYPE = "Field dtype must be of DataType.";
    const FIELD_DIM_MISMATCH = "Collection field dim (%s) and entity field dim (%s) mismatch for field %s.";
    const FIELD_NOT_FOUND_IN_SCHEMA = "Field %s not found among schema fields";
    const FIELD_NOT_FOUND_IN_ENTITIES = "Field %s not found among entity fields";
    const FIELD_TYPE = "The field of schema type must be FieldSchema.";
    const FIELD_TYPE_MISMATCH = "Collection field type (%s) and entity field type (%s) mismatch for field %s.";
    const FIELDS_NUM_INCONSISTENT = "The data fields number does not match with schema.";
    const EMPTY_FIELD_NAME = "Field name must not be empty";
    const EMPTY_SCHEMA = "The field of the schema cannot be empty.";
    const IS_PRIMARY_TYPE = "Param is_primary must be bool type.";
    const MISSING_ENTITY_PARAM = "Missing param in entities, a field must have %s";
    const NO_COLLECTION_NAME = "No collection name specified.";
    const NO_CONNECION_ALIAS = "No connection alias specified.";
    const NO_VECTOR = "No vector field is found.";
    const PRIMARY_KEY_NOT_EXIST = "Primary field must in dataframe.";
    const PRIMARY_KEY_NOT_FOUND = "Primary key not found.";
    const PRIMARY_KEY_ONLY_ONE = "Primary key field can only be one.";
    const PRIMARY_KEY_TYPE = "Primary key type must be DataType::INT64 or DataType::VARCHAR.";
    const ROW_NUM_MISALIGNED = "Row num misaligned for field %s";
    const SCHEMA_INCONSISTENT = "The collection already exist, but the schema is not the same as the schema passed in.";
    const UNKNOWN_DATA_TYPE = "Data type is unknown.";
}