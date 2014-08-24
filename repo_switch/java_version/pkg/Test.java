package pkg;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

public class Test {

	final static String config_file = "config.properties";
	final static String template = "../sources.list.template";
	final static String sources_list = "../sources.list";

	public static void main(String[] args) {
		List<Mirror> list = Test.loadMirrors();
		
		int mirrorId = 3;
		
		Mirror choosenMirror = list.get(mirrorId);
		
		// copy template to work on it
		Test.runSubProcess("cp " + template + " " + sources_list);
		
		// use regexp to insert mirror prefix into sources.list file
		String[] sedCmd= {"sed",  "--in-place", "s|http://|http://"+choosenMirror.getPrefix() + "|g", "../sources.list"};
		Test.runSubProcess(sedCmd, true);
		


		System.exit(0);
	}

	public static List<Mirror> loadMirrors() {
		ArrayList<Mirror> res = new ArrayList<Mirror>();
		MyProperties prop = MyProperties.load(config_file);
		int cpt = 1;
		boolean loop;
		do {
			loop = false;
			String titleKey = "mirrors." + cpt + ".title";
			String prefixKey = "mirrors." + cpt + ".prefix";
			String titleValue = prop.get(titleKey);
			String prefixValue = prop.get(prefixKey);
			if (titleValue != null && prefixValue != null) {
				res.add(new Mirror(titleValue, prefixValue));
				loop = true;
			}
			cpt++;
		} while (loop);
		return res;
	}

	public static int runSubProcess(String command) {		
		String[] cmd = command.split(" ");
		return Test.runSubProcess(cmd, false);
	}
	
	public static int runSubProcess(String[] command, boolean displayoutputs) {
		int res = -1;
		ProcessBuilder pb = new ProcessBuilder(command);
		try {
			Process proc = pb.start();
			res = proc.waitFor();
			if(displayoutputs) {
				InputStream is = proc.getInputStream();
				Test.printInputStream(is);
				
				InputStream es = proc.getErrorStream();
				Test.printInputStream(es);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		return res;
	}

	public static void printInputStream(InputStream is) {
		BufferedReader br = new BufferedReader(new InputStreamReader(is));
		String line;
		try {
			line = br.readLine();
			while(line != null) {
				System.out.println(line);
				line = br.readLine();
			}
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
}
