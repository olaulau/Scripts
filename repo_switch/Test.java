import java.io.FileReader;
import java.io.BufferedReader;
import java.io.IOException;


public class Test {

    public static void main(String[] args) {
    	try {
    		BufferedReader reader = new BufferedReader(new FileReader("/etc/lsb-release"));
    		String line = reader.readLine();
       		System.out.println(line);
       		reader.close();
    	}
    	catch(IOException ex) {
    		System.err.println(ex);
    	}
        
    }

}

