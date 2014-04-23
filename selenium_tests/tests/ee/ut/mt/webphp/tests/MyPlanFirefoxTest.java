package ee.ut.mt.webphp.tests;

import java.util.concurrent.TimeUnit;

import junit.framework.TestCase;

import org.junit.*;
import org.openqa.selenium.*;
import org.openqa.selenium.chrome.ChromeDriver;

public class MyPlanFirefoxTest extends TestCase {
	private WebDriver driver;
	private String baseUrl;
	private StringBuffer verificationErrors = new StringBuffer();

	@Before
	public void setUp() throws Exception {
		driver = new ChromeDriver();
		baseUrl = "http://regix.dev/";
		driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
		addUsers();
		addEvents();
	}
	
	public void addUsers() throws Exception {
		driver.get(baseUrl + "login/logout");
		
		// Register users
		driver.get(baseUrl + "reg");
	    driver.findElement(By.id("name")).clear();
	    driver.findElement(By.id("name")).sendKeys("testownplan1");
	    driver.findElement(By.id("username")).clear();
	    driver.findElement(By.id("username")).sendKeys("testownplan1");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("testownplan1");
	    driver.findElement(By.id("repassword")).clear();
	    driver.findElement(By.id("repassword")).sendKeys("testownplan1");
	    driver.findElement(By.id("email")).clear();
	    driver.findElement(By.id("email")).sendKeys("testownplan1");
	    driver.findElement(By.name("submit")).click();
	    
 		driver.get(baseUrl + "reg");
 	    driver.findElement(By.id("name")).clear();
 	    driver.findElement(By.id("name")).sendKeys("testownplan2");
 	    driver.findElement(By.id("username")).clear();
 	    driver.findElement(By.id("username")).sendKeys("testownplan2");
 	    driver.findElement(By.id("password")).clear();
 	    driver.findElement(By.id("password")).sendKeys("testownplan2");
 	    driver.findElement(By.id("repassword")).clear();
 	    driver.findElement(By.id("repassword")).sendKeys("testownplan2");
 	    driver.findElement(By.id("email")).clear();
 	    driver.findElement(By.id("email")).sendKeys("testownplan2");
 	    driver.findElement(By.name("submit")).click();
	    
 	    // Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    // Add group
	    driver.get(baseUrl + "groups/add/testownplan");
	    
	    // Add group permissions
	    driver.get(baseUrl + "groups/add_permission/testownplan/myplan_own_plan");
	    
	    // Add user to group
	    driver.get(baseUrl + "groups/add_user/testownplan");
	    driver.findElement(By.xpath("//td[contains(text(), \"testownplan1\")]/../td/a")).click();
	}
	
	public void removeUsers() throws Exception {
	    
 	    // Login as admin
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("test");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("test_password");
	    driver.findElement(By.name("submit")).click();
	    
	    // Remove users
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"testownplan1\")]/../../td/div/a")).click();
	    driver.get(baseUrl + "uac");
	    driver.findElement(By.xpath("//td/div[contains(text(), \"testownplan2\")]/../../td/div/a")).click();
	    
	    // Remove group
	    driver.get(baseUrl + "groups/delete/testownplan");
	}
	
	public void addEvents() throws Exception {
	}
	
	public void removeEvents() throws Exception {
	}
	
	// Generic complex asserts.
	public void assertMyPlanUI() throws Exception {
		assertTrue(isElementPresent(By.id("navigation_home")));
		assertTrue(isElementPresent(By.id("navigation_prev")));
		assertTrue(isElementPresent(By.id("navigation_now")));
		assertTrue(isElementPresent(By.id("navigation_next")));
		assertTrue(isElementPresent(By.id("time_slot_0")));
		assertTrue(isElementPresent(By.id("time_slot_1")));
		assertTrue(isElementPresent(By.id("time_slot_2")));
		assertTrue(isElementPresent(By.id("time_slot_3")));
		assertTrue(isElementPresent(By.id("time_slot_4")));
		assertTrue(isElementPresent(By.id("time_slot_5")));
		assertTrue(isElementPresent(By.id("time_slot_6")));
		assertTrue(isElementPresent(By.id("time_slot_7")));
		assertTrue(isElementPresent(By.id("time_slot_8")));
		assertTrue(isElementPresent(By.id("time_slot_9")));
	}
	
	@Test
	public void testMyPlanLogin() throws Exception {
		// Only users with permission myplan_own_plan can access their own plan.
		
		// Login as testownplan1 (has access)
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("testownplan1");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("testownplan1");
	    driver.findElement(By.name("submit")).click();
	    
		driver.get(baseUrl + "myplan");
		assertMyPlanUI();
		
		// Login as testownplan2 (no access)
	    driver.get(baseUrl + "login");
	    driver.findElement(By.id("login")).clear();
	    driver.findElement(By.id("login")).sendKeys("testownplan2");
	    driver.findElement(By.id("password")).clear();
	    driver.findElement(By.id("password")).sendKeys("testownplan2");
	    driver.findElement(By.name("submit")).click();
	    
		driver.get(baseUrl + "myplan");
		assertTrue(isElementPresent(By.className("error_message_block")));
	}

	@After
	public void tearDown() throws Exception {
		removeEvents();
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