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
//		List<Mirror> list = Test.loadMirrors();
//		
//		int mirrorId = 1;
//		
//		Mirror choosenMirror = list.get(mirrorId);
		
		// copy template to work on it
		Test.runSubProcess("cp " + template + " " + sources_list);
		
		// use regexp to insert mirror prefix into sources.list file
//		String sedCommand = "sed --in-place 's|http://|http://fr.|g' ../sources.list";
//		System.out.println(sedCommand);
		// sed --in-place "s|archive.ubuntu.com|${PREFIXES[opt]}archive.ubuntu.com|g" sources.list
//		Test.runSubProcess(sedCommand);
		
		
		String[] sedCmd= {"sed",  "--in-place", "s|http://|http://fr.|g", "../sources.list"};
		Test.runSubProcessBis(sedCmd, true);
		
		
		
		
		
//		MyProperties prop = MyProperties.load(config_file);
//		System.out.println(prop.toString());

//		List<Mirror> list = Test.loadMirrors();
//		System.out.println(list);

//		String[] cmd = { "sleep", "2"};
//		Test.runSubProcessBis(cmd, true);

		System.exit(1);
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
		int res = -1;
		Runtime run = Runtime.getRuntime();
		try {
			Process proc = run.exec(command);
			InputStream in = proc.getInputStream();
			res = proc.waitFor();
			InputStream stdout = proc.getInputStream();
			InputStream stderr = proc.getErrorStream();
			//while print ..
			
		} catch (Exception ex) {
			ex.printStackTrace();
		}
		return res;
	}
	
	
	public static int runSubProcessBis(String[] command, boolean displayoutputs) {
		int res = -1;
		ProcessBuilder pb = new ProcessBuilder(command);
//		pb.redirectErrorStream(true);
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
