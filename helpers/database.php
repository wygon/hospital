<?php
function connectDB()
{
    $conn = mysqli_connect(DB_HOST,  DB_USER, DB_PASS, DB_NAME);
    if(!$conn)
        die("Connection failed");

    return $conn;
}

function execute($connection, $sql, $params = [])
{
    $preparedQuery = mysqli_prepare($connection, $sql);

    if (!$preparedQuery)
        die("SQL query error: " . mysqli_error($connection));

    if (!empty($params)) {
        $types = "";
        foreach ($params as $param) {
            if (is_int($param)) $types .= "i";
            elseif (is_double($param)) $types .= "d";
            elseif (is_bool($param)) $types .= "i";
            else $types .= "s";
        }

        // $preparedQuery->bind_param($types, ...$params);
        mysqli_stmt_bind_param($preparedQuery, $types, ...$params);
    }

    // $preparedQuery->execute();
    if (!mysqli_stmt_execute($preparedQuery)) {
        die("Error: " . mysqli_stmt_error($preparedQuery));
    }

    // $result = $preparedQuery->get_result();
    $result = mysqli_stmt_get_result($preparedQuery);

    // Zamykamy statement po wykonaniu
    // Uwaga: jeśli potrzebujesz insert_id, nie zamykaj stmt tutaj, 
    // ale w tym prostym projekcie wystarczy nam pobranie go z obiektu połączenia.

    if ($result === false) {
        $success = mysqli_stmt_affected_rows($preparedQuery) >= 0;
        mysqli_stmt_close($preparedQuery);
        return $success;
    }

    mysqli_stmt_close($preparedQuery);
    return $result;
    // return $result ? $result : $preparedQuery;
}

// Zamiast $this->conn->insert_id
function lastInsertId($connection)
{
    return mysqli_insert_id($connection);
}

// Zamiast $result->fetch_assoc()
function querySingle($connection, $sql, $params = [])
{
    $result = execute($connection, $sql, $params);
    
    // Sprawdzamy, czy execute zwróciło obiekt wyniku (dla SELECT)
    if ($result instanceof mysqli_result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result); // Dobra praktyka: zwalniamy pamięć

        return $data;
    }
    return null;
}

// Zamiast $result->fetch_all(MYSQLI_ASSOC)
function queryAll($connection, $sql, $params = [])
{
    $result = execute($connection, $sql, $params);
    
    if ($result instanceof mysqli_result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        
        return $data;
    }
    return [];
}

// Zamiast $this->conn->close()
function closeConn($connection)
{
    return mysqli_close($connection);
}
?>