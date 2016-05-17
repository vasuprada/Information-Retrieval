import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;

import org.apache.tika.exception.TikaException;
import org.apache.tika.metadata.Metadata;
import org.apache.tika.parser.ParseContext;
import org.apache.tika.parser.pdf.PDFParser;
import org.apache.tika.sax.BodyContentHandler;

import org.xml.sax.SAXException;

public class pdfParse{

   public static void main(final String[] args) throws IOException,TikaException {

      
      
      File bigTextPdfFile=new File("C:\\Users\\vasu\\Desktop\\bigpdf.txt");
	  try(PrintWriter pdfWriter = new PrintWriter(new BufferedWriter(new FileWriter(bigTextPdfFile,true))))
	  {	 
	     
	      String pdfDir="C:\\Users\\vasu\\Desktop\\Crawled\\pdf_docs\\";
			File pdfDirFolder = new File(pdfDir);
			File[] listOfPdfFiles = pdfDirFolder.listFiles();
		    int length = listOfPdfFiles.length;
		    
		  
		      for (File pdfFile : listOfPdfFiles)
		      {
		    	  
		    	  if (pdfFile.isFile()) {
		    		  System.out.println(pdfFile.getName());
		    		  BodyContentHandler handler = new BodyContentHandler();
		    		  
				      try 
				      {
				    	 
				    	  Metadata metadata = new Metadata();
					      FileInputStream inputstream = new FileInputStream(new File(pdfDir+pdfFile.getName()));
					      ParseContext pcontext = new ParseContext();
					      
					     
					      PDFParser pdfparser = new PDFParser(); 
						pdfparser.parse(inputstream, handler, metadata, pcontext);
				      } catch (SAXException e) 
				      {
					
						e.printStackTrace();
				      }
			      
			    
				       pdfWriter.println( handler.toString());	
				      
		    	  }
		    	  
		    	  
		      }
		  
	  }catch(IOException e )
	  {
		  e.printStackTrace();
	  }
   }
}