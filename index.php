<?php
/*
 *
 * Populate MySQL Table Using faker
 * 
 */
require_once('./vendor/autoload.php');
try{
    $count = 100;
    $faker = \Faker\Factory::create();

    //Connecting MySQL Database
    $pdo  = new PDO('mysql:
      host=localhost;
      dbname=ab-exercise',
      'root',
      'root', 
      array(PDO::ATTR_PERSISTENT => true)
    );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    //Drop the table 
    $stmt = $pdo->prepare("truncate table bookings");
    $stmt->execute();

    //Insert the data
    $sql = 'INSERT INTO bookings (name, email, street, city, state, mailcode, bookingType, bookingDate) 
    VALUES (:name, :email, :street, :city, :state, :mailcode, :bookingType, :bookingDate)';
    $stmt = $pdo->prepare($sql);

    for ($i=0; $i < $count; $i++) {
        $date = $faker->dateTime($max = 'now', 'UTC')->format('Y-m-d H:i:s');
        if ($i % 2 == 1) {
          $bookingType = "Housekeeping";
        } else {
          $bookingType = "Dog Walking";
        }

        $stmt->execute(
            [
                ':name' => $faker->name,
                ':email' => $faker->email,
                ':street' => $faker->streetAddress,
                ':city' => $faker->city,
                ':state' => $faker->state,
                ':mailcode' => $faker->postcode,
                ':bookingType' => $bookingType,
                ':bookingDate' => $date
            ]
        );
    }
} catch(Exception $e){
    echo '<pre>';print_r($e);echo '</pre>';exit;
}
?>