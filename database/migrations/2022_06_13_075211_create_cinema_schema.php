<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
        Schema::create('cinema', function($table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('total_cinema_hall');
            $table->timestamps();
        });

        Schema::create('cinema_hall', function($table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('total_seats');
            $table->integer('cinema_id')->unsigned();
            $table->foreign('cinema_id')->references('id')->on('cinema')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cinema_seats', function($table) {
            $table->increments('id');
            $table->string('label', 20);
            $table->integer('seat_number');
            $table->enum('type', ['vip-seat', 'couple-seat', 'super-vip', 'whatever']);
            $table->integer('cinema_hall_id')->unsigned();
            $table->foreign('cinema_hall_id')->references('id')->on('cinema_hall')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->longText('description')->nullable();
            $table->string('duration');
            $table->string('genre');
            $table->timestamp('release_date');
            $table->timestamps();
        });

        Schema::create('shows', function($table) {
            $table->increments('id');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('cinema_hall_id')->unsigned();
            $table->foreign('cinema_hall_id')->references('id')->on('cinema_hall')->onDelete('cascade');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->timestamps();
        });

        // Schema::create('users', function($table) {
        //     $table->increments('id');
        //     $table->string('name');
        //     $table->string('password', 50);
        //     $table->string('email', 20);
        //     $table->string('phone', 20);
        //     $table->timestamps();
        // });

        Schema::create('booking', function($table) {
            $table->increments('id');
            $table->integer('total_seat');
            $table->timestamp('booking_time');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('show_seat', function($table) {
            $table->increments('id');
            $table->decimal('price');
            $table->integer('cinema_seat_id')->unsigned();
            $table->foreign('cinema_seat_id')->references('id')->on('cinema_seats')->onDelete('cascade');
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->integer('booking_id')->unsigned()->nullable();
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
