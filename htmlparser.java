import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;

import org.apache.tika.exception.TikaException;
import org.apache.tika.metadata.Metadata;
import org.apache.tika.parser.ParseContext;
import org.apache.tika.parser.html.HtmlParser;
import org.apache.tika.sax.BodyContentHandler;

import org.xml.sax.SAXException;

public class htmlParser{

   public static void main(final String[] args) throws IOException,TikaException {

      
      
      File bigTextHtmlFile=new File("C:\\Users\\vasu\\Desktop\\big_html.txt");
	  try(PrintWriter htmlWriter = new PrintWriter(new BufferedWriter(new FileWriter(bigTextHtmlFile,true))))
	  {	 
	   
	      String htmlDir="C:\\\\Users\\vasu\\Desktop\\Crawled\\html_docs\\";
			File htmlDirFolder = new File(htmlDir);
			File[] listOfHtmlFiles = htmlDirFolder.listFiles();
		    int length = listOfHtmlFiles.length;

		  
		      for (File htmlFile : listOfHtmlFiles)
		      {
		    	  
		    	  if (htmlFile.isFile()) {
		    		  System.out.println(htmlFile.getName());
		    		  BodyContentHandler handler = new BodyContentHandler();
		    		  
				      try 
				      {
				    	
				    	  Metadata metadata = new Metadata();
					      FileInputStream inputstream = new FileInputStream(new File(htmlDir+htmlFile.getName()));
					      ParseContext pcontext = new ParseContext();
					      
					      
					      HtmlParser htmlparser = new HtmlParser(); 
						htmlparser.parse(inputstream, handler, metadata, pcontext);
				      } catch (SAXException e) 
				      {
						
						e.printStackTrace();
				      }
			      
			    
				       htmlWriter.println( handler.toString());	
				      
		    	  }
		    	  
		    	  
		      }
		  
	  }catch(IOException e )
	  {
		  e.printStackTrace();
	  }
   }
}