========
Overview
========

Requirements
============

* PHP 7.1 or higher
* If you use Apache, you will need the ``mod_rewrite`` module.
* You will need to redirect all requests that do not lead to existing files to your index file, most of the times this
  is the :code:`index.php` file in your public root.

Installation
============

The recommended way to install Road Trip is with `Composer <http://getcomposer.org>`_.

You can install Road Trip by using the Composer CLI.

.. code-block:: bash

    composer require stevenliebregt/road-trip:^1.0

Alternatively you can add the dependency to your existing ``composer.json`` file.

.. code-block:: js

    {
        "require": {
            "stevenliebregt/road-trip": "^1.0"
        }
    }

After installing it, you need to require Composer's autoloader in your code.

License
=======

This project is licensed using the `MIT license <http://opensource.org/licenses/MIT>`_.

    Copyright (c) 2018 Steven Liebregt

    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
    documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
    rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
    permit persons to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
    Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
    WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
    OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
    OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.