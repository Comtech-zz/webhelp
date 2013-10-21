Differences between the DITA Open Toolkit bundled with Oxygen and a regular DITA Open Toolkit distribution
downloaded from the DITA OT project:

http://sourceforge.net/projects/dita-ot/files/

--------ADDITIONAL INSTALLED PLUGINS---------------

plugins\com.oxygenxml.webhelp   ->   Plugin for generating WebHelp output developed and implemented by Oxygen.

plugins\mathml                  ->   Plugin for minimal MathML specialization support implemented by Oxygen.

plugins\net.sourceforge.dita4publishers.common.html  -> Plugins from the Dita For Publishers project used to generate EPUB output.
plugins\net.sourceforge.dita4publishers.common.mapdriven
plugins\net.sourceforge.dita4publishers.common.xslt
plugins\net.sourceforge.dita4publishers.epub 

--------REMOVED RESOURCES----------------
The following directories have been removed:

tools
doc

The following libraries have been removed (and the equivalent ones in "OXYGEN_INSTALL_DIR\lib" are used instead):

lib\saxon\saxon9.jar
lib\saxon\saxon9-dom.jar
lib\xercesImpl.jar
lib\xml-apis.jar
lib\icu4j.jar

The bundled ANT distribution "tools\ant" has been removed and the "OXYGEN_INSTALL_DIR\tools\ant" is used instead.

----------PATCHES---------------------

The following patches have been made:

plugins/org.dita.pdf2/xsl/fo/commons.xsl

  EXM-18109 Also break line before title of figure if the image has a placement break.
  EXM-18138 Add a little extra space after inline image
 
plugins/org.dita.pdf2/build_xep.xml
plugins/org.dita.pdf2/build.xml
  
  EXM-10624 Also reference Java Classpath in order to load Oxygen patches 
  
resource/commonltr.css
  EXM-18359, EXM-18138, EXM-17248 Small style changes for HTML output
  
  
xsl/map2htmlhelp/map2hhpImpl.xsl

  EXM-18626 Changes for better CHM rendering
  
xsl/xslhtml/dita2htmlImpl.xsl

  EXM-18109 Show image title below image
  EXM-23575 Use either proportional or fixed column widths
  EXM-18109 Show figures without figure number
  
xsl/contexts.xsl
    EXM-18224  Create "contexts.xml" for Eclipse Help

xsl/map2javahelpmap.xsl
  EXM-18765 Fixed broken links on children of reused topic refs
  
xsl/map2javahelptoc.xsl
  EXM-18359 Correctly look for title of map
  EXM-22437 Removed extra spaces due to frontmatter, toc, backmatter
  EXM-21663 Normalize title text
  
xsl/map2javahelpset.xsl
  Normalize map title
  
build_dita2javahelp.xml
  EXM-18027 Correct generated help IDs
  
build_init.xml 
  EXM-21393 Do not specify the JVM architecture, set value forced to empty
  EXM-23321 display warning if Saxon EE not licensed because not run from Oxygen
  EXM-17248 Added 'clean.output' parameter
  
build_preprocess_template.xml
  EXM-17248 Added 'clean.output' parameter
  
build_template.xml
  EXM-17248 Added 'clean.output' parameter
  
plugins/org.dita.pdf2/build_fop.xml
    EXM-27325 Added the macro runFOPInExternalJVM for running Apache FOP in external JVM 

plugins/org.dita.pdf2/build_fop_forked_task.xml
    EXM-27325 A new file added by Oxygen that contains the macro runFOPInExternalJVM 
                          for running Apache FOP in external JVM 

plugins/org.dita.pdf2/build_xep.xml
    EXM-27325 Added an <echo> with debugging info: maxJavaMemory param

Added documentation annotations to DTDs in "dtd" folder.