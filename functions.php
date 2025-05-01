<?php
/**
 * Get movies from the database
 * 
 * @param mysqli $conn Database connection
 * @param bool $watched Whether to get watched or unwatched movies
 * @return array Array of movies
 */
function getMovies($conn, $watched = false) {
    $watched = $watched ? 1 : 0;
    $sql = "SELECT * FROM movies WHERE watched = ? ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $watched);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $movies = [];
    
    while($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
    
    $stmt->close();
    
    return $movies;
}

/**
 * Add a new movie
 * 
 * @param mysqli $conn Database connection
 * @param array $data Movie data
 * @return array Result with success status and message
 */
function addMovie($conn, $data) {
    // Validate required fields
    if (empty($data['title'])) {
        return ['success' => false, 'message' => 'Názov filmu je povinný.'];
    }
    
    // Prepare SQL statement
    $sql = "INSERT INTO movies (title, description, image_url) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Set default values for optional fields
    $description = !empty($data['description']) ? $data['description'] : null;
    $image_url = !empty($data['image_url']) ? $data['image_url'] : null;
    
    $stmt->bind_param("sss", $data['title'], $description, $image_url);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Film bol úspešne pridaný.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Chyba pri pridávaní filmu: ' . $error];
    }
}

/**
 * Update movie watched status
 * 
 * @param mysqli $conn Database connection
 * @param int $id Movie ID
 * @param bool $watched Whether the movie is watched or not
 * @return array Result with success status and message
 */
function updateMovieStatus($conn, $id, $watched = true) {
    $watched = $watched ? 1 : 0;
    
    $sql = "UPDATE movies SET watched = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $watched, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Stav filmu bol úspešne aktualizovaný.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Chyba pri aktualizácii stavu filmu: ' . $error];
    }
}

/**
 * Update movie rating
 * 
 * @param mysqli $conn Database connection
 * @param int $id Movie ID
 * @param int $rating Rating (1-5)
 * @return array Result with success status and message
 */
function updateRating($conn, $id, $rating) {
    // Validate rating
    $rating = intval($rating);
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Neplatné hodnotenie. Musí byť od 1 do 5.'];
    }
    
    $sql = "UPDATE movies SET rating = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $rating, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Hodnotenie bolo úspešne aktualizované.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Chyba pri aktualizácii hodnotenia: ' . $error];
    }
}

/**
 * Delete a movie
 * 
 * @param mysqli $conn Database connection
 * @param int $id Movie ID
 * @return array Result with success status and message
 */
function deleteMovie($conn, $id) {
    $sql = "DELETE FROM movies WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Film bol úspešne vymazaný.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Chyba pri vymazávaní filmu: ' . $error];
    }
}

/**
 * Update ratings for Mia or Tomino
 * 
 * @param mysqli $conn Database connection
 * @param int $id Movie ID
 * @param int $rating Rating (1-5)
 * @param string $rater Name of the rater (mia or tomino)
 * @return array Result with success status and message
 */
function updateUserRating($conn, $id, $rating, $rater) {
    // Validate rating
    $rating = intval($rating);
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Neplatné hodnotenie. Musí byť od 1 do 5.'];
    }
    
    // Validate rater
    $rater = strtolower($rater);
    if ($rater !== 'mia' && $rater !== 'tomino') {
        return ['success' => false, 'message' => 'Neplatný hodnotiteľ.'];
    }
    
    $column = 'rating_' . $rater;
    
    $sql = "UPDATE movies SET $column = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $rating, $id);
    
    if ($stmt->execute()) {
        // Calculate and update average rating
        $result = $conn->query("SELECT rating_mia, rating_tomino FROM movies WHERE id = $id");
        if ($row = $result->fetch_assoc()) {
            $mia_rating = $row['rating_mia'];
            $tomino_rating = $row['rating_tomino'];
            
            // Calculate average only if both ratings are available
            if ($mia_rating > 0 && $tomino_rating > 0) {
                $avg_rating = round(($mia_rating + $tomino_rating) / 2);
                
                // Update the average rating
                $conn->query("UPDATE movies SET rating = $avg_rating WHERE id = $id");
            }
        }
        
        $stmt->close();
        return ['success' => true, 'message' => 'Hodnotenie bolo úspešne aktualizované.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Chyba pri aktualizácii hodnotenia: ' . $error];
    }
} 