

## Arbor Education Test Task

This submission contains my codebase for the task solution. It took way beyond the suggested 3 hours including system design and planning, therefore incomplete, but the produced code I believe is high quality.

- Endpoints for Creating a Puzzle, sending a word solution and finishing the puzzle.
- In the storage folder, there is a Puzzle Game.postman_collection.json file which contains the endpoints and example data in use with them
- The leaderbord feature is missing, as it was out of the timeframe of this task - databases and initial tests were created for it

## Design Decisions and Difficulties

I opted using Laravel as my framework as it seemed to be the nicest and cleanest way to develop this small application. 

I had to think about the valid English word validation until I found different API providers which can be used for this task (I also looked at the Pspell ¶
 library, but it is no longer supported as of php 8.4). I use the most elegant, Dictionaryapi.dev for validation, but it does not support random word which I found required to provide at least one solvable word in each random string, so that is provided by a designated random word api. So basically the puzzle word is a random string of a certain number of characters and one random valid English word, shuffled together. Testing these results seems to be incredibly difficult and some kind of weighting or other solution probably should be used. In a real world scenario I would go back to the relevant stakeholders or product owners to discuss this feature and find a proper solution. The other option I consider is to generate the random string only from valid words. This will be important in the following problem.

Solving the task which says provide a list of possible words in the remaining puzzle text is incredible resource-hungry as a recursive permutation function have to run to determine them. I even replaced it with one which checks for valid English words only, but it still runs out of memory time. I submitted this code to showcase the thinking and the work behind it, but the proper way in my opinion here would be to change the specification to reduce the number of possibilities - either with using only real words as seed or some kind of weighting.

The instructions do not require user handling, nor specifies if a single puzzle can be solved by different persons or not, so those were not taken into consideration. A fake user_id is required to create a puzzle, representing that a user handling mechanism could be applied there.

## Setup Instructions

PHP 8.1+ & Composer are prerequisites.


First, install the package dependencies

```
composer install
```

You can either run the project with the in-built webserver, or in docker, I used the former for development:

```
php artisan serve
```

Finally, run the migrations

```
php artisan migrate:fresh
```

## Endpoints usage

### Create New Puzzle

```http
POST /new_puzzle HTTP/1.1
Host: localhost
Content-Type: application/json
```

> ### Request form parameters

| `user_id` | _integer_ <br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8

{
    "id": 1,
    "puzzle": "ozrefibhrkdlaonrbpibpxeavlr"
}
```

> ### Unsuccessful Response Example

```http
HTTP/1.1 422 OK
Content-Type: application/json; charset=UTF-8

{
    "user_id": [
        "The user id field is required."
    ]
}
```

### Submit new word solution

```http
POST /submit_word HTTP/1.1
Host: localhost
Content-Type: application/json
```

> ### Request form parameters

| `puzzle_id` | _integer_, Existing puzzle ID <br>
| `word` | _string_ <br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8

{
    "message": "The submitted word have been accepted.",
    "puzzle": "ozfihrkdonrpibpxeavr",
    "current_score": 7
}
```

> ### Unsuccessful Response Example

```http
HTTP/1.1 422 OK
Content-Type: application/json; charset=UTF-8

{
    "message": "The submitted word cannot be made from the puzzle string."
}
```

### Finish puzzle

```http
POST /finish HTTP/1.1
Host: localhost
Content-Type: application/json
```

> ### Request form parameters

| `puzzle_id` | _integer_, Existing puzzle ID <br>

> ### Successful Response Example

```http
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8

{
    "message": "The puzzle ended by the request of the user.",
    "remaining_words": "toy boy fox box",
    "final_score": 123
}
```

> ### Unsuccessful Response Example

```http
HTTP/1.1 422 OK
Content-Type: application/json; charset=UTF-8

{
    "puzzle_id": [
        "The puzzle id field is required."
    ]
}
```
