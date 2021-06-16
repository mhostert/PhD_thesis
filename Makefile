DOC  := main

all: $(DOC).tex */*.tex
	-pdflatex -pdf $(DOC).tex
	-bibtex $(DOC).aux
	-pdflatex -pdf $(DOC).tex
	-pdflatex -pdf $(DOC).tex

purge:
	-rm -f *.{aux,dvi,log,bbl,blg,brf,fls,toc,thm,out,fdb_latexmk}
	-rm -f */*.{aux,dvi,log,bbl,blg,brf,fls,toc,thm,out,fdb_latexmk}

clean: purge

.PHONY: all purge clean