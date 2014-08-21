package pkg;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Properties;

public class Test {
		
	final static String config_file = "config.properties";

	public static void main(String[] args) {
//		MyProperties prop = MyProperties.load(config_file);
//		System.out.println(prop.toString());
		
//		List<Mirror> list = Test.loadMirrors();
//		System.out.println(list);
		
//		Runtime run = Runtime.getRuntime();
//		try {
//			String cmd = "sleep 2";
//			Process proc = run.exec(cmd);
//			proc.waitFor();
//		} catch (Exception ex) {
//			ex.printStackTrace();
//		}
	}

	public static List<Mirror> loadMirrors() {
		ArrayList<Mirror> res = new ArrayList<Mirror>();
		try {
			Properties prop = new Properties();
			FileInputStream fis = new FileInputStream(config_file);
			prop.load(fis);
			
			int cpt = 1;
			boolean loop;
			do {
				loop = false;
				String titleKey = "mirrors." + cpt + ".title";
				String prefixKey = "mirrors." + cpt + ".prefix";
				String titleValue = prop.getProperty(titleKey);
				String prefixValue = prop.getProperty(prefixKey);
				if(titleValue != null && prefixValue != null) {
					res.add(new Mirror(titleValue, prefixValue));
					loop = true;
				}
				cpt ++;
			}
			while(loop);
		} catch (IOException ex) {
			System.err.println(ex);
		}
		return res;
	}

	public static void loadAndWriteProperties(String fileName) {
		MyProperties prop = MyProperties.load(fileName);
		System.out.println(prop.toString());
	}
}
