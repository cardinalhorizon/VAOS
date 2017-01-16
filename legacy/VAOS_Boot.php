<?php
/**
 * VIRTUAL AIRLINE OPERATIONS SYSTEM PROJECT
 * =========================================
 *
 * COPYRIGHT 2017 TAYLOR BROAD. ALL RIGHTS RESERVED.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

require 'vendor/autoload.php';

$capsule = new Illuminate\Database\Capsule\Manager;


// Lets do some boot loading real quick to pull our connection in just in-case
$dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

// VAOS Connection Details
$capsule->addConnection(
    ['driver' => 'mysql',
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'database' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => $_ENV['DB_PREFIX'],
        'strict' => true,
        'engine' => null], 'default');

// phpVMS Database Connection Details for Local support.
$capsule->addConnection(
    ['driver' => 'mysql',
        'host' => $_ENV['LEGACY_HOST'],
        'port' => $_ENV['LEGACY_PORT'],
        'database' => $_ENV['LEGACY_DATABASE'],
        'username' => $_ENV['LEGACY_USERNAME'],
        'password' => $_ENV['LEGACY_PASSWORD'],
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => $_ENV['LEGACY_PREFIX'],
        'strict' => true,
        'engine' => null], 'legacy');


$capsule->setAsGlobal();
$capsule->bootEloquent();