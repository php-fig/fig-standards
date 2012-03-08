Indenting and Line Length
=========================

Use an indent of 4 spaces; do not use tabs. The use of spaces helps to avoid
problems with diffs, patches, history, and annotations, and provides
fine-grained sub-indentation when aligning elements on consecutive lines.

Lines should be limited to 75-85 characters in length. This is based on known
human cognitive limitations, not the technical limitations of screen windows
or text editors. When necessary, it is allowed to split a single statement
across subsequent lines, per rules noted elsewhere in this guide.

Some further insight into the 75-85 characters rule:

> How many words per line can a person scan, and still be able to grasp the
> content of the line in the context of the surrounding lines? Printing and
> publishing typographers figured out a long time ago that most people can
> read no more than 10 to 12 words per line before they have trouble
> differentiating lines from each other. (A “word” is counted as five
> characters on average.) Even allowing for a 25% to 50% increase, that
> brings us up to 15 words. Times 5 characters per word, that means 75
> characters on a line.
> 
> So the style guide limitation on line length is not exactly arbitrary. It
> is about the developer’s ability to effectively scan and comprehend
> strings of text, not about the technical considerations of terminals and
> text-editors.

-- "Line Length, Volume, and Density"
   <http://paul-m-jones.com/archives/276>
