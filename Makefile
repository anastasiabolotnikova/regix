doc: clean
	doxygen

doc-php: clean
	phpdoc -d backend/ -t docs/ --sourcecode

clean:
	rm -rf docs
