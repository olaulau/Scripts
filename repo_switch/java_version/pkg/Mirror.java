package pkg;
public class Mirror {
	
	private String title;
	private String prefix;
	
	
	public Mirror(String title, String prefix) {
		this.title = title;
		this.prefix = prefix;
	}
	
	
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}

	public String getPrefix() {
		return prefix;
	}
	public void setPrefix(String prefix) {
		this.prefix = prefix;
	}

}
