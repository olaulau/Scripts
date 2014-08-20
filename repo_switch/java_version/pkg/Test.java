package pkg;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map.Entry;
import java.util.Properties;
import java.util.Set;

public class Test {
	
	final static String etc_release = "/etc/lsb-release";
	final static String config_file = "config.properties";

	public static void main(String[] args) {
//		Test.loadAndWriteProperties(etc_release);
//		Test.loadAndWriteProperties(config_file);
		
		List<Mirror> list = Test.loadMirrors();
		System.out.println(list);
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
		try {
			Properties prop = new Properties();
			FileInputStream fis = new FileInputStream(fileName);
			prop.load(fis);

			Set<Entry<Object, Object>> set = prop.entrySet();
			for (Entry<Object, Object> entry : set) {
				System.out.println("'" + entry.getKey() + "'" + " => " + "'"
						+ entry.getValue() + "'");
			}
		} catch (IOException ex) {
			System.err.println(ex);
		}
	}
}
