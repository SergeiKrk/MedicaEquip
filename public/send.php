<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $formName = isset($_POST['FormName']) ? htmlspecialchars($_POST['FormName']) : 'Без имени';
    $name = isset($_POST['Name']) ? htmlspecialchars($_POST['Name']) : 'Не указано';
    $phone = isset($_POST['Phone']) ? htmlspecialchars($_POST['Phone']) : 'Не указан';
    $email = isset($_POST['Mail']) && !empty($_POST['Mail']) ? htmlspecialchars($_POST['Mail']) : null;
    $city = isset($_POST['Cite']) && !empty($_POST['Cite']) ? htmlspecialchars($_POST['Cite']) : null;
    $inn = isset($_POST['INN']) && !empty($_POST['INN']) ? htmlspecialchars($_POST['INN']) : null;

    // Создаем тело письма
    $body = "<h3>Новая заявка с формы: {$formName}</h3>";
    $body .= "<p><strong>Имя:</strong> {$name}</p>";
    $body .= "<p><strong>Телефон:</strong> {$phone}</p>";

    // Добавляем дополнительные поля, если они не пустые
    if ($email) {
        $body .= "<p><strong>Email:</strong> {$email}</p>";
    }
    if ($city) {
        $body .= "<p><strong>Город:</strong> {$city}</p>";
    }
    if ($inn) {
        $body .= "<p><strong>ИНН:</strong> {$inn}</p>";
    }

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.jino.ru';
        $mail->SMTPAuth = true;
        $mail->Username = 'zakaz@rossnab73.ru';
        $mail->Password = 'p3wCGhebchb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('zakaz@rossnab73.ru', 'Новая заявка');
        $mail->addAddress('kryukovs.ru@gmail.com', 'Получатель'); // Укажите свою почту

        $mail->isHTML(true);
        $mail->Subject = 'Заявка с rossnab73.ru';
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        
        // Перенаправление на страницу благодарности
        header('Location: /thank-you/');
        exit; // Останавливаем дальнейшее выполнение скрипта
    } catch (Exception $e) {
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
} else {
    echo 'Доступ запрещен';
}
