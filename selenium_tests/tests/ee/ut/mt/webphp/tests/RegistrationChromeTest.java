package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;
import org.openqa.selenium.*;
import org.openqa.selenium.chrome.ChromeDriver;
	
public class RegistrationChromeTest extends TestCase {
	private WebDriver driver;
	private String baseUrl;
	private StringBuffer verificationErrors = new StringBuffer();
	
	@Before
	public void setUp() throws Exception {
		// Download chromedriver (http://code.google.com/p/chromedriver/downloads/list)
	    System.setProperty("webdriver.chrome.driver", "F:/Selenium/chromedriver.exe");
		driver = new ChromeDriver();
		baseUrl = "http://regix.dev/";
		driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
	}

	public void removeUsers() throws Exception {
	    
 	    // Login as administrator
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    // Remove users
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"selenium\")]/../../td/div/a")).click();
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"noEmail\")]/../../td/div/a")).click();
	    
	    assertTrue(isElementPresent(By.className("success_message_block")));
	}
	
	@Test
	public void testRegistration() throws Exception {
		
	    // Register user success 1: all data is correct
		driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("selenium");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("seleniumtest");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("selenium_password1");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("selenium_password1");
	    driver.findElement(By.id("email")).clear();
	    driver.findElement(By.id("email")).sendKeys("selenium1@ut.ee");
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("success_message_block")));
	    
	    // Register user success 2: no email entered
 		driver.get(baseUrl + "reg");
 	    driver.findElement(By.id("name")).clear();
 	    driver.findElement(By.id("name")).sendKeys("noEmail");
 	    driver.findElement(By.id("username")).clear();
 	    driver.findElement(By.id("username")).sendKeys("noEmail");
 	    driver.findElement(By.id("password")).clear();
 	    driver.findElement(By.id("password")).sendKeys("noEmail_password1");
 	    driver.findElement(By.id("repassword")).clear();
 	    driver.findElement(By.id("repassword")).sendKeys("noEmail_password1");
 	    driver.findElement(By.name("submit")).click();
 	    
 	    assertTrue(isElementPresent(By.className("success_message_block")));
	    
	    // Register user failure 1: user with such login already exists
	    
	    driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("selenium2");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("seleniumtest");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("selenium_password2");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("selenium_password2");
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("error_message_block")));
	    
	    // Register user failure 2: password and repassword do not match
	    
	    driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("selenium2");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("seleniumtest2");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("selenium_password");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("password_selenium");
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("error_message_block")));
	    
	    // Register user failure 3: no name entered
	    
	    driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("seleniumtest2");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("selenium_password");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("password_selenium");
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("error_message_block")));
	    
	    // Register user failure 4: no username entered
	    
	    driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("selenium2");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("selenium_password");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("password_selenium");
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("error_message_block")));
	    
	    
	    // Register user failure 5: no password/repassword entered
	    
	    driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("selenium3");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("seleniumtest2");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.name("submit")).click();
	    
	    assertTrue(isElementPresent(By.className("error_message_block")));
	    
	}

	@After
	public void tearDown() throws Exception {
		removeUsers();
		driver.quit();
		String verificationErrorString = verificationErrors.toString();
		if (!"".equals(verificationErrorString)) {
			fail(verificationErrorString);
		}
	}

	private boolean isElementPresent(By by) {
		try {
			driver.findElement(by);
			return true;
		} catch (NoSuchElementException e) {
			return false;
		}
	}
}