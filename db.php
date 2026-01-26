<?php
/**
 * =====================================================
 * DATABASE HELPER
 * PostgreSQL & Oracle (PDO ONLY)
 * FETCH MODE: OBJECT
 * =====================================================
 */

/* =====================================================
 | POSTGRESQL
 * ===================================================== */

function pgsql(): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn  = 'pgsql:host=localhost;port=5432;dbname=simpad';
    $user = 'postgres';
    $pass = 'password';

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('PostgreSQL connection error : ' . $e->getMessage());
    }

    return $pdo;
}

/**
 * Execute INSERT / UPDATE / DELETE
 */
function pgsql_exec(string $sql, array $params = []): bool
{
    $stmt = pgsql()->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Fetch all
 */
function pgsql_all(string $sql, array $params = []): array
{
    $stmt = pgsql()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch one
 */
function pgsql_one(string $sql, array $params = [])
{
    $stmt = pgsql()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * INSERT PostgreSQL
 * ⚠️ WAJIB pakai RETURNING
 */
function pgsql_insert(string $sql, array $params = [])
{
    $stmt = pgsql()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function pgsql_update(string $sql, array $params = []): bool
{
    return pgsql_exec($sql, $params);
}

function pgsql_delete(string $sql, array $params = []): bool
{
    return pgsql_exec($sql, $params);
}


/* =====================================================
 | ORACLE (PDO OCI)
 * ===================================================== */

function oracle(string $mode = 'SID'): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = 'localhost';
    $port = '1521';
    $sid  = 'ORCL';        // SID
    $svc  = 'ORCLPDB1';    // SERVICE_NAME
    $user = 'simpad';
    $pass = 'password';

    if ($mode === 'SERVICE') {
        // SERVICE_NAME
        $dsn = "oci:dbname=(DESCRIPTION=
            (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
            (CONNECT_DATA=(SERVICE_NAME=$svc))
        );charset=AL32UTF8";
    } else {
        // SID (default)
        $dsn = "oci:dbname=(DESCRIPTION=
            (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
            (CONNECT_DATA=(SID=$sid))
        );charset=AL32UTF8";
    }

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            // Oracle lebih stabil
            PDO::ATTR_EMULATE_PREPARES   => true,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('Oracle connection error : ' . $e->getMessage());
    }

    return $pdo;
}

/**
 * Execute INSERT / UPDATE / DELETE
 */
function oracle_exec(string $sql, array $params = []): bool
{
    $stmt = oracle()->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Fetch all
 */
function oracle_all(string $sql, array $params = []): array
{
    $stmt = oracle()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch one
 */
function oracle_one(string $sql, array $params = [])
{
    $stmt = oracle()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * INSERT Oracle (pakai RETURNING)
 */
function oracle_insert(string $sql, array $params = [], ?string $returnField = null)
{
    $stmt = oracle()->prepare($sql);

    if ($returnField !== null) {
        $id = null;
        $stmt->bindParam(
            ':' . $returnField,
            $id,
            PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT,
            32
        );
    }

    $stmt->execute($params);
    return $returnField !== null ? $id : true;
}

function oracle_update(string $sql, array $params = []): bool
{
    return oracle_exec($sql, $params);
}

function oracle_delete(string $sql, array $params = []): bool
{
    return oracle_exec($sql, $params);
}
