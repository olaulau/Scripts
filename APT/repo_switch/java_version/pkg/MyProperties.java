package pkg;

import java.io.FileInputStream;
import java.io.IOException;
import java.util.Properties;
import java.util.Set;
import java.util.Map.Entry;

public class MyProperties {
	
	private Properties prop;
	
	private MyProperties(Properties p) {
		this.prop = p;
	}
	
	public static MyProperties load(String fileName) {
		Properties p = new Properties();
		try {
			FileInputStream fis = new FileInputStream(fileName);
			p.load(fis);
		} catch (IOException ex) {
			System.err.println(ex);
		}
		return new MyProperties(p);
	}
	
	public String toString() {
		StringBuffer buf = new StringBuffer();
		Set<Entry<Object, Object>> set = prop.entrySet();
		for (Entry<Object, Object> entry : set) {
			buf.append("'" + entry.getKey() + "'" + " => " + "'"
					+ entry.getValue() + "'" + "\n");
		}
		return buf.toString();
	}
	
	public String get(String key) {
		return (String)prop.get(key);
	}

}
