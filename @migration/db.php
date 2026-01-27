<?php
/**
 * =====================================================
 * DATABASE HELPER
 * PostgreSQL & Oracle (PDO ONLY)
 * FETCH MODE: OBJECT
 * NO MULTI CONNECTION (PASS CONNECTION)
 * =====================================================
 */


/* =====================================================
 | POSTGRESQL
 * ===================================================== */

/**
 * Create PostgreSQL connection (call ONCE)
 */

function oldSimpadPgsql(): PDO
{
    $dsn  = 'pgsql:host=173.16.6.2;port=5432;dbname=simpad';
    $user = 'postgres';
    $pass = 'simpad1!2@';

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('PostgreSQL connection error : ' . $e->getMessage());
    }
}

function pgsql(): PDO
{
    $dsn  = 'pgsql:host=localhost;port=5432;dbname=simpad';
    $user = 'postgres';
    $pass = 'postgres';

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('PostgreSQL connection error : ' . $e->getMessage());
    }
}

/**
 * Execute INSERT / UPDATE / DELETE
 */
function pgsql_exec(PDO $conn, string $sql, array $params = []): bool
{
    $stmt = $conn->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Fetch all rows
 */
function pgsql_all(PDO $conn, string $sql, array $params = []): array
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch single row
 */
function pgsql_one(PDO $conn, string $sql, array $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * INSERT PostgreSQL
 * ⚠️ WAJIB pakai RETURNING
 */
function pgsql_insert(PDO $conn, string $sql, array $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

function pgsql_update(PDO $conn, string $sql, array $params = []): bool
{
    return pgsql_exec($conn, $sql, $params);
}

function pgsql_delete(PDO $conn, string $sql, array $params = []): bool
{
    return pgsql_exec($conn, $sql, $params);
}


/* =====================================================
 | ORACLE (PDO OCI)
 * ===================================================== */

/**
 * Create Oracle connection (call ONCE)
 */
function oracle(string $mode = 'SID'): PDO
{
    $host = 'localhost';
    $port = '1521';
    $sid  = 'ORCL';        // SID
    $svc  = 'ORCLPDB1';    // SERVICE_NAME
    $user = 'simpad';
    $pass = 'password';

    if ($mode === 'SERVICE') {
        $dsn = "oci:dbname=(DESCRIPTION=
            (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
            (CONNECT_DATA=(SERVICE_NAME=$svc))
        );charset=AL32UTF8";
    } else {
        $dsn = "oci:dbname=(DESCRIPTION=
            (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
            (CONNECT_DATA=(SID=$sid))
        );charset=AL32UTF8";
    }

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            // Oracle lebih stabil
            PDO::ATTR_EMULATE_PREPARES   => true,
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        die('Oracle connection error : ' . $e->getMessage());
    }
}

/**
 * Execute INSERT / UPDATE / DELETE
 */
function oracle_exec(PDO $conn, string $sql, array $params = []): bool
{
    $stmt = $conn->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Fetch all rows
 */
function oracle_all(PDO $conn, string $sql, array $params = []): array
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch single row
 */
function oracle_one(PDO $conn, string $sql, array $params = [])
{
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * INSERT Oracle (RETURNING INTO)
 */
function oracle_insert(
    PDO $conn,
    string $sql,
    array $params = [],
    ?string $returnField = null
) {
    $stmt = $conn->prepare($sql);

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

function oracle_update(PDO $conn, string $sql, array $params = []): bool
{
    return oracle_exec($conn, $sql, $params);
}

function oracle_delete(PDO $conn, string $sql, array $params = []): bool
{
    return oracle_exec($conn, $sql, $params);
}
