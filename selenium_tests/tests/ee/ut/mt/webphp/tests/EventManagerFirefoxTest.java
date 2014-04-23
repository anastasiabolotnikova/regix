package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;
import org.junit.*;
import static org.junit.Assert.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;

public class EventManagerFirefoxTest {
  private WebDriver driver;
  private String baseUrl;
  private StringBuffer verificationErrors = new StringBuffer();

  @Before
  public void setUp() throws Exception {
    driver = new FirefoxDriver();
    baseUrl = "http://regix/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
  }
  
  public void removeEvents() throws Exception {
		// Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    //Delete event
	    driver.get(baseUrl + "emc");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"selenium_test_event_added\")]/../../td/div/a")).click();
	    assertEquals("Event deleted", driver.findElement(By.cssSelector("div.success_message_block")).getText());
  }

  @Test
  public void testGuest() throws Exception {
	// Login as admin
	driver.get(baseUrl + "login");
	driver.findElement(By.id("login")).clear();
	driver.findElement(By.id("login")).sendKeys("test");
	driver.findElement(By.id("password")).clear();
	driver.findElement(By.id("password")).sendKeys("test_password");
	driver.findElement(By.name("submit")).click();
	
	//Edit Event
	driver.get(baseUrl + "emc");
    driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[2]/td[5]/div")).click();
    driver.findElement(By.id("from")).clear();
    driver.findElement(By.id("from")).sendKeys("2014-03-12 11:00:00");
    driver.findElement(By.id("to")).clear();
    driver.findElement(By.id("to")).sendKeys("2014-03-12 12:00:00");
    driver.findElement(By.id("description")).clear();
    driver.findElement(By.id("description")).sendKeys("Selenium_test_event");
    driver.findElement(By.id("service")).clear();
    driver.findElement(By.id("service")).sendKeys("tserv1");
    driver.findElement(By.name("submit")).click();
    assertEquals("Event modified", driver.findElement(By.cssSelector("div.success_message_block")).getText());
    driver.findElement(By.linkText("<< Back to the main page")).click();
    driver.findElement(By.xpath("//div[@id='content']/a[4]/span")).click();
    assertEquals("Selenium_test_event", driver.findElement(By.xpath("//div[@id='content']/div/div/table/tbody/tr[2]/td[5]/div")).getText());
    
    //Add Event
    driver.findElement(By.name("submit")).click();
    assertEquals("Calendar :: Regix", driver.getTitle());
    assertEquals("Services", driver.findElement(By.cssSelector("th")).getText());
    driver.findElement(By.linkText("Test Service 1")).click();
    driver.findElement(By.id("next")).click();
    driver.findElement(By.linkText("10")).click();
    driver.findElement(By.linkText("10:00 - 11:00")).click();
    driver.findElement(By.id("comment")).clear();
    driver.findElement(By.id("comment")).sendKeys("selenium_test_event_added");
    driver.findElement(By.name("reg_event")).click();
    assertEquals("Your registration was successful", driver.findElement(By.cssSelector("div.success_message_block")).getText());
   }

  @After
  public void tearDown() throws Exception {
	removeEvents();
    driver.quit();
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }
}
