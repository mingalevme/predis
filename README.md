# mingalevme/predis
## Extended version of predis/predis — added support for pseudo-unique lists (based on ordered set)

## Available methods:
* **zrpush($key, $value1, $value2 = null, .., $valueN = null)**: Insert all the specified values at the tail of the list stored at key.
* **zlpush($key, $value1, $value2 = null, .., $valueN = null)**: Insert all the specified values at the head of the list stored at key.
* **zlpop($key)**: Removes and returns the first element of the list stored at key.
* **zrpop($key)**: Removes and returns the last element of the list stored at key.

## Important
**Methods z[l|r]pop don't support transactions**

## Example

```php
$client = new \Mingalevme\Predis\Client();

$client->del('queue');

$client->zrpush('queue', '1');
$client->zrpush('queue', '2');
$client->zrpush('queue', '3');

echo "\n\nStep 1\n";
print_r($client->zrange('queue', 0, -1));

$client->zrpush('queue', '1');
$client->zrpush('queue', '2');
$client->zrpush('queue', '3');

echo "\n\nStep 2\n";
print_r($client->zrange('queue', 0, -1));

$client->zlpush('queue', '-1');
$client->zlpush('queue', '-2');
$client->zlpush('queue', '-3');

echo "\n\nStep 3\n";
print_r($client->zrange('queue', 0, -1));

echo "\n\nStep 4\n";
$el = $client->zrpop('queue');
print_r($el);

echo "\n\nStep 5\n";
$el = $client->zlpop('queue');
print_r($el);

echo "\n\nStep 6\n";
while ($el = $client->zlpop('queue')) {
    echo "\nvalue: {$el}\n";
    echo "list: ";
    print_r($client->zrange('queue', 0, -1));
}
```

### Output
```
Step 1
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
)


Step 2
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
)


Step 3
Array
(
    [0] => -3
    [1] => -2
    [2] => -1
    [3] => 1
    [4] => 2
    [5] => 3
)


Step 4
3

Step 5
-3

Step 6

value: -2
list: Array
(
    [0] => -1
    [1] => 1
    [2] => 2
)

value: -1
list: Array
(
    [0] => 1
    [1] => 2
)

value: 1
list: Array
(
    [0] => 2
)

value: 2
list: Array
(
)
```
