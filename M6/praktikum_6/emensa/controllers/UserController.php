<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/benutzer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');

class UserController {
    // Show the login form
    public function showLoginForm() {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']); // Clear the error message after displaying

        return view('examples.pages.login', ['error' => $error]);
    }

    // Handle login verification
    public function verifyLogin(RequestData $request) {
        // Get email and password from POST data
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        // Validate input
        if (!$email || !$password) {
            $_SESSION['error'] = "Bitte geben Sie eine gültige E-Mail und ein Passwort ein.";
            logger()->warning('Failed login attempt: Missing email or password', ['email' => $email]);
            header("Location: /anmeldung");
            exit;
        }

        // Fetch user by email
        $user = getUserByEmail($email);

        if (!$user) {
            $_SESSION['error'] = "Benutzer nicht gefunden.";
            logger()->warning('Failed login attempt: User not found', ['email' => $email]);
            header("Location: /anmeldung");
            exit;
        }

        // Hash the input password with the salt
        $salt = 'E-MensaSalt123';
        $hashedPassword = hash('sha256', $salt . $password);

        // Start database transaction
        $link = connectdb();
        $link->begin_transaction();

        try {
            // Check if password is correct
            if ($user['passwort'] !== $hashedPassword) {
                updateLastError($user['id']); // Update last error timestamp and increment error count
                $link->commit(); // Commit the error update
                logger()->warning('Failed login attempt: Incorrect password', ['user_id' => $user['id'], 'email' => $email]);
                throw new Exception("Ungültiges Passwort.");
            }

            // Call stored procedure to increment login counter
            incrementLoginCounter($user['id']);

            // Update last login timestamp
            updateLastLogin($user['id']);

            // Commit transaction
            $link->commit();

            // Successful login: Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['admin'] = $user['admin']; // Store admin status in the session
            logger()->info('User logged in successfully', ['user_id' => $user['id'], 'email' => $email]);

            // Redirect to the main page
            unset($_SESSION['error']); // Clear any previous errors
            header("Location: /");
            exit;

        } catch (Exception $e) {
            // Rollback transaction in case of failure
            $link->rollback();
            $_SESSION['error'] = $e->getMessage(); // Store error message in session
            logger()->error('Login failed', ['email' => $email, 'reason' => $e->getMessage()]);
            header("Location: /anmeldung");
            exit;
        } finally {
            // Close the database connection
            $link->close();
        }
    }

    public function logout() {
        logger()->info("ABMELDUNG");
        session_destroy(); // Destroy the session to log out the user
        header("Location: /"); // Redirect to the main page
        exit;
    }

    function deleteRating($ratingId, $userId) {
        $link = connectdb();
        $stmt = $link->prepare("
        DELETE FROM bewertung
        WHERE id = ? AND benutzer_id = ?
    ");
        $stmt->bind_param("ii", $ratingId, $userId);
        return $stmt->execute();
    }
}
