package testproject;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;

import org.openqa.selenium.*;
import org.openqa.selenium.chrome.ChromeDriver;

public class CalendarChromeTest extends TestCase {
  private WebDriver driver;
  private String baseUrl;
  private StringBuffer verificationErrors = new StringBuffer();

  @Before
  public void setUp() throws Exception {
	System.setProperty("webdriver.chrome.driver", "C:/Selenium/chromedriver.exe");
	driver = new ChromeDriver();
    baseUrl = "http://regix/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
    addUser();
  }

  public void addUser() throws Exception {
		driver.get(baseUrl + "login/logout");
		
		// Register users
		driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("testevent1");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("testevent1");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("testevent1");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("testevent1");
	    driver.findElement(By.id("email")).clear();
	    driver.findElement(By.id("email")).sendKeys("testevent1");
	    driver.findElement(By.name("submit")).click();
	}
	
	public void removeUser() throws Exception {
	    
	    // Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    // Remove user
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"testevent1\")]/../../td/div/a")).click();
	}
	
	public void removeEvents() throws Exception {
		// Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    //Delete guest event
	    driver.get(baseUrl + "/");
	    driver.findElement(By.xpath("//div[@id='content']/a[4]/span")).click();
	    assertEquals("selenium_test_event_guest", driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[5]/td[5]/div")).getText());
	    assertEquals("Guest", driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[5]/td[6]/div")).getText());
	    driver.findElement(By.xpath("(//a[contains(text(),'remove')])[4]")).click();
	    assertEquals("Event deleted", driver.findElement(By.cssSelector("div.success_message_block")).getText());
	    
	    //Delete testevent1 event
	    driver.get(baseUrl + "/");
	    driver.findElement(By.xpath("//div[@id='content']/a[4]/span")).click();
	    assertEquals("selenium_test_event_testevent1", driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[5]/td[5]/div")).getText());
	    assertEquals("testevent1", driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[5]/td[6]/div")).getText());
	    driver.findElement(By.xpath("(//a[contains(text(),'remove')])[4]")).click();
	    assertEquals("Event deleted", driver.findElement(By.cssSelector("div.success_message_block")).getText());
	}
	
	@Test
	public void testRegisterEvent(){
		//Guest may register event
		
		driver.get(baseUrl + "/");
	    driver.findElement(By.xpath("//div[@id='content']/a[3]/span")).click();
	    driver.findElement(By.linkText("Test Service 1")).click();
	    driver.findElement(By.id("next")).click();
	    driver.findElement(By.linkText("2")).click();
	    driver.findElement(By.linkText("10:00 - 11:00")).click();
	    driver.findElement(By.id("comment")).clear();
	    driver.findElement(By.id("comment")).sendKeys("selenium_test_event");
	    driver.findElement(By.id("comment")).clear();
	    driver.findElement(By.id("comment")).sendKeys("selenium_test_event_guest");
	    driver.findElement(By.name("reg_event")).click();
	    assertEquals("Your registration was successful", driver.findElement(By.cssSelector("div.success_message_block")).getText());
	    
		//Registered user testevent1 may register event
	    driver.get(baseUrl + "login/logout");
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("testevent1");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("testevent1");
	    driver.findElement(By.name("submit")).click();
	    
	    driver.get(baseUrl + "/");
	    driver.findElement(By.xpath("//div[@id='content']/a[3]/span")).click();
	    driver.findElement(By.linkText("Test Service 2")).click();
	    driver.findElement(By.id("next")).click();
	    driver.findElement(By.linkText("15")).click();
	    driver.findElement(By.id("next")).click();
	    driver.findElement(By.linkText("13:00 - 14:00")).click();
	    driver.findElement(By.id("comment")).clear();
	    driver.findElement(By.id("comment")).sendKeys("selenium_test_event_testevent1");
	    driver.findElement(By.name("reg_event")).click();
	    assertEquals("Your registration was successful", driver.findElement(By.cssSelector("div.success_message_block")).getText());
	    
		//User changed his mind (Cancel button)
	    driver.get(baseUrl + "/");
	    driver.findElement(By.xpath("//div[@id='content']/a[3]/span")).click();
	    driver.findElement(By.linkText("Test Service 2")).click();
	    driver.findElement(By.id("next")).click();
	    driver.findElement(By.linkText("16")).click();
	    driver.findElement(By.id("next")).click();
	    driver.findElement(By.linkText("13:00 - 14:00")).click();
	    driver.findElement(By.id("comment")).clear();
	    driver.findElement(By.id("comment")).sendKeys("selenium_test_event_testevent1");
	    driver.findElement(By.name("cancel")).click();
	    assertEquals("Calendar :: Regix", driver.getTitle());
	    assertEquals("Services", driver.findElement(By.cssSelector("th")).getText());
	}
	
	@After
	public void tearDown() throws Exception {
		removeEvents();
		removeUser();
		driver.quit();
		String verificationErrorString = verificationErrors.toString();
		if (!"".equals(verificationErrorString)) {
			fail(verificationErrorString);
		}
	}
}
