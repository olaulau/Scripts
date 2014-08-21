package pkg;

import java.util.ArrayList;
import java.util.List;

public class Test {

	final static String config_file = "config.properties";
	final static String template = "../sources.list.template";
	final static String sources_list = "../sources.list";

	public static void main(String[] args) {
		// MyProperties prop = MyProperties.load(config_file);
		// System.out.println(prop.toString());

		List<Mirror> list = Test.loadMirrors();
		System.out.println(list);

//		Test.runSubProcess("sleep 2");

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
			res = proc.waitFor();
		} catch (Exception ex) {
			ex.printStackTrace();
		}
		return res;
	}
}
