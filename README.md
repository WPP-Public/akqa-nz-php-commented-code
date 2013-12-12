Commented Code Detection for PHP
==================

This script scans a directory tree for PHP files and analyses their contents to find multi-line comment sections that are likely PHP code. Each section is given a score representing its likelihood of being commented code.

## Installation
Checkout this repository and create a symlink in /usr/local/bin to the executable file ```comment-sniffer```.

## Usage
```comment-sniffer /path/to/my/code```

## Example Output
```
File: /path/to/my/project/source/mysite/code/DataObjects/HubLink.php
	Line 42 [score: 12]
	Line 54 [score: 8]
	Line 66 [score: 8]
	Line 78 [score: 8]
	Line 90 [score: 8]
	
File: /path/to/my/project/source/mysite/code/Forms/SearchForm.php
	Line 67 [score: 21]
	Line 68 [score: 32]
	Line 70 [score: 18]
```

## How it works
Contents of each comment are parsed for PHP tokens using ```token_get_all()```. Each token has a value assigned to it that is commensurate with its likelihood of being PHP code. For instance, ```__FUNCTION__``` gets a score of 10, as that text is very unlikely to be written in a readable phrase, where as ```if``` gets a score of only 1, because it is very likely to appear in textual content. Each token augments the score for a given block. Each non-qualifying token (e.g. T_STRING) decrements the score by 1. If the final score is higher than the given ```tolerance```, the result is added to the report.

## Configuration
If scores are returning false positives, you can specify a tolerance, which is the score at which a result is included in the report. Default value is 5.

```comment-sniffer /path/to/my/code --tolerance 10```

