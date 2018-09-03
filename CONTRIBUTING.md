## PHP Style Guide
All rules and guidelines in this section apply to PHP files unless otherwise noted. References to PHP/HTML files can be interpreted as files that primarily contain HTML, but use PHP for templating purposes.

#### Legend
``.`` Space

``⇥`` Tab

``↲`` Enter / Return

``...`` Code

### Table of Contents
[1. Files](#1-files)

[2. Skeleton](#2-skeleton)

[3. PHP Tags](#3-php-tags)

[4. Namespaces](#4-namespaces)

[5. Comments](#5-comments)

[6. Includes](#6-includes)

[7. Formatting](#7-formatting)

[8. Functions](#8-functions)

[9. Classes](#9-classes)

[10. Control Structures](#10-control-structures)

### 1. Files
This section describes the format and naming convention of PHP files.

#### File Format
1. **Character encoding** MUST be set to UTF-8 without BOM.
	- In Sublime &rarr; ``File`` > ``Save with Encoding`` > ``UTF-8``
2. **Line endings** MUST be set to Unix (LF)
	- In Sublime &rarr; ``View`` > ``Line Endings`` > ``Unix``

#### Filename
1. **Files or directories containing classes** MUST capitalize the beginning of each word.
	- e.g. ``SomeClass.php``
2. **Files or directories not containing classes** MUST be all lowercase.
	 - e.g. ``bootstrap.php``

[&uarr; Table of Contents](#table-of-contents)

### 2. Skeleton
This section showcases a barebones PHP file with its minimum requirements.

Line by line breakdown:
* **Line 1**: PHP open tag.
* **Line 2**: Blank line.
* **Line 3**: Your code.
* **Line 4**: Blank line.

```php 
<?php

// Your code

```

[&uarr; Table of Contents](#table-of-contents)

### 3. PHP Tags
This section describes the use of PHP tags in PHP and PHP/HTML files.

1. **Open tag** MUST be on its own line and MUST be followed by a blank line.
	- e.g. ``<?php`` ``↲`` ``↲`` ``...``
2. **Close tag** MUST NOT be used in PHP only files. (See [stackoverflow](https://stackoverflow.com/questions/32124566/why-some-php-files-do-not-end-with-the-closing-bracket) and [php.net](http://php.net/manual/en/language.basic-syntax.phptags.php).)
	- i.e. No ``?>``
3. **Short open tag** MUST NOT be used.
	- e.g. ``<?`` &rarr; ``<?php``

[&uarr; Table of Contents](#table-of-contents)

### 4. Namespaces
This section describes how to use one or more namespaces and their naming convention.

1. Namespace declaration MUST be the first statement and MUST be followed by a blank line.
	- e.g. ``<?php`` ``↲`` ``↲`` ``namespace SomeCompany;`` ``↲`` ``↲`` ``...``
2. Namespace name MUST start with a capital letter and MUST be CamelCase.
	- e.g. ``namespace SomeCompany;``
3. Multiple namespaces MUST use the curly brace syntax.
	- e.g. ``namespace SomeCompany { ... }``
4. Magic constant SHOULD be used to reference the namespace name.
	- e.g. ``__NAMESPACE__``

[&uarr; Table of Contents](#table-of-contents)

### 5. Comments
1. **Single-line comments** MUST use two forward slashes.
	- e.g. ``// My comment.``
2. **Multi-line comments** MUST use the block format.
	- e.g. ``/**`` ``↲`` ``* My comment.`` ``↲`` ``*/``
3. **Header comments** SHOULD use the the block format.
	- e.g. ``/**`` ``↲`` ``* Name of code section.`` ``↲`` ``*/``
4. **Divider comments** SHOULD use the block format with asterisks in between.
	- e.g. ``/**`` ``75 asterisks`` ``*/``
5. **Comments** MUST be on their own line.
	- e.g. ``↲`` ``// My comment.``
6. **Blocks of code** SHOULD be explained or summarized.
	- e.g. ``// Retrieve employee records from the database.``
7. **Ambiguous numbers** MUST be clarified.
	- e.g. ``// Iterate over 100 employee records.``
8. **External variables** MUST be clarified.
	- e.g. ``// Database adapter include in bootstrap.php.``

[&uarr; Table of Contents](#table-of-contents)

### 6. Includes
This section describes the format for including and requiring files.

1. **Include/require once** SHOULD be used.
	- e.g. ``include`` &rarr; ``include_once``
	- e.g. ``require`` &rarr; ``require_once``
2. **Parenthesis** MUST NOT be used.
	- e.g. ``include_once('SomeClass.php');`` &rarr; ``include_once 'SomeClass.php';``
3. **Purpose of include** MUST be documented with a comment.
	- e.g. ``// Provides Slim Framework container.`` ``↲`` ``require_once 'bootstrap.php';``

[&uarr; Table of Contents](#table-of-contents)

### 7. Formatting
1. **Line length** SHOULD be 80 characters or less. Developers SHOULD strive to keep each line of their code under 80 characters where possible and practical, however, longer lines are acceptable in some circumstances. The maximum length of any line of PHP code is 120 characters.
	- e.g. ``|---- 80+ characters ----|`` &rarr; refactor expression and / or break list values.
2. **Line indentation** MUST be accomplished using four spaces.
	- e.g. ``function SomeFunction ()`` ``↲`` ``{`` ``↲`` ``⇥`` ``...`` ``↲`` ``}``
3. **Blank lines** SHOULD be added between logical blocks of code.
	- e.g. ``...`` ``↲`` ``↲`` ``...``
4. **Text alignment** MUST be accomplished using spaces.
	- e.g. ``$Variable`` ``.`` ``.`` ``.`` ``= ...;``
5. **Trailing whitespace** MUST NOT be present after statements or on blank lines.
	- i.e. no ``...`` ``.`` ``.`` ``↲`` ``.`` ``↲`` ``...``
6. **Variables** MUST start with a capital letter and MUST be CamelCase.
	- e.g. ``$SomeVariable``
7. **Global variables** MUST be declared one variable per line and MUST be indented after the first.
	- e.g. ``global $Variable1,`` ``↲`` ``⇥`` ``$Variable2;``
8. **Constants** MUST be all UPPERCASE and words MUST be separated by an underscore.
	- e.g. ``SOME_CONSTANT``
9. **Statements** MUST be placed on their own line and MUST end with a semicolon.
	- e.g. ``↲`` ``SomeFunction();`` ``↲``
10. **Operators** MUST be surrounded by a space.
	- e.g. ``$SomeInteger = 1 + 2;``
	- e.g. ``$SomeString .= '';``
11. **Unary operators** MUST be attached to their variable or integer.
	- e.g. ``$Index++;``
	- e.g. ``--$Index;``
12. **Concatenation period** MUST be surrounded by a space.
	- e.g. ``echo 'Hello, ' . $Username;``
13. **Single quotes** MUST be used for completely literal strings.
	- e.g. ``echo 'Hello world!';``
14. **Double quotes** with brackets and dollar sign inside SHOULD be used for variable expansion. This is the most speed efficient and readable. (See [php.net](http://php.net/manual/en/language.types.string.php#120160).)
	- e.g. ``echo "Hello, {$Username};"``

[&uarr; Table of Contents](#table-of-contents)

### 8. Functions
This section describes the format for function names, calls, arguments, and declarations.

1. **Function name** MUST start with a capital letter and MUST be CamelCase.
2. **Function prefix** MUST start with a verb.
	- e.g. ``Get``
	- e.g. ``Update``
3. **Function call** MUST NOT have a space between the function name and open parenthesis.
	- e.g. ``SomeFunction();``
4. **Function arguments**
	- MUST NOT have a space before the comma.
	- MUST have a space after the comma.
	- MAY use line breaks for long arguments.
		- MUST then place each argument on its own line.
		- MUST then indent each argument once.
	- MUST be ordered from required to optional first.
	- MUST be ordered from high to low importance second.
	- MUST use descriptive defaults.
	- SHOULD use type hinting when possible and practicle.
	- e.g. ``SomeFunction( string $Message = 'Hello world!' );``
5. **Function declaration** MUST be documented use [phpDocumentor](https://docs.phpdoc.org/references/phpdoc/index.html) tag style and SHOULD include:
	- Short description.
	- (Optional) Long description, if needed.
	- @access: ``private`` or ``protected`` (assumed ``public``).
	- @author: Author name.
	- @global: Global variables function uses, if applicable.
	- @param: Parameters with data type, variable name, and description.
	- @return: Return data type, if applicable.
	- @since: Version the function was introduced in.
	- @throws: Exceptions the function throws, if applicable.
6. **Function return**
	- MUST occur as early as possible.
	- MUST be initialized prior, at the top.
	- MUST be preceded by blank line, except inside control statement.
	- e.g. ``if (! $Expression ) { return False; }``

[&uarr; Table of Contents](#table-of-contents)

### 9. Classes
This section describes class files, names, definitions, properties, methods, and instantiation.

1. **Class file** MUST only contain one definition.
	- e.g. ``User.php`` &rarr; ``class User``.
	- e.g. ``Employee.php`` &rarr; ``class Employee``.
2. **Class namespace** MUST be defined and MUST include vendor name if dealing with a library.
	- e.g. ``namespace App\Model;``
	- e.g. ``namespace Anteris\SomeLibrary;``
3. **Class name** MUST start with a capital letter and MUST be CamelCase.
	- e.g. ``SomeClass``
4. **Class documentation** MUST be present and MUST use use [phpDocumentor](https://docs.phpdoc.org/references/phpdoc/index.html) tag style .
	- e.g. ``@author``
	- e.g. ``@global``
	- e.g. ``@package``
5. **Class definition** MUST place curly braces on their own line.
	- e.g. ``class Employee`` ``↲`` ``{`` ``↲`` ``...`` ``↲`` ``}``
6. **Class properties**
	- MUST follow variable standards above.
	- MUST specify visibility.
	- e.g. ``public $Variable1;``
	- e.g. ``private $Variable2;``
7. **Class methods**
	- MUST follow function standards above.
	- MUST specify visibility.
	- e.g. ``public function Example1();``
	- e.g. ``private function Example2();``
8. **Class instance**
	- MUST start with a capital letter.
	- MUST be CamelCase.
	- Must include parenthesis.
	- e.g. ``$Employee = new Employee();``

[&uarr; Table of Contents](#table-of-contents)

### 10. Control Structures
This section defines the layout and usage of control structures.

- **Keyword** MUST be followed by a space.
	- e.g. ``if (``
	- e.g. ``switch (``
- **Opening parenthesis** SHOULD be followed by a space.
	- e.g. ``( $Expression``
- **Closing parenthesis** SHOULD be followed by a space.
	- e.g. ``$Expression )``
- **Opening brace** MUST be preceded by a space and MUST be on its own line.
	- e.g. ``( $Expression )`` ``↲`` ``{`` ``...``
- **Structure body** MUST be indented once and MUST be enclosed with curly braces (no shorthand).'
	- e.g. ``if ( $Expression )`` ``↲`` ``{`` ``⇥`` ``...`` ``↲`` ``}``
- **Closing brace** MUST start on the next line.
	- e.g. ``...`` ``↲`` ``}``
- **Avoid nesting** whenever possible.

In addition to the rules above, some control structures have additional requirements:
1. **if, else if, else**
	- ``else if`` MUST be used instead of ``elseif``.
2. **switch, case**
	- **Case statement** MUST be indented once.
		- e.g. ``⇥`` ``case 1:``
	- **Case body** MUST be indented twice.
		- e.g. ``⇥`` ``⇥`` ``SomeFunction();``
	- **Break keyword** MUST be indented once.
		- e.g. ``⇥`` ``break;``
	- **Case blocks** MUST be separated by one blank line.

[&uarr; Table of Contents](#table-of-contents)
